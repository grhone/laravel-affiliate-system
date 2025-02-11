<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Payment;
use Illuminate\Support\Facades\DB;
use Grhone\LaravelAffiliateSystem\Mail\PaymentSentMail;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    protected $paypalService;

    public function __construct()
    {
        $this->paypalService = new PayPalService();
    }

    public function processPaymentForAffiliate(Affiliate $affiliate)
    {
        $payoutAmount = number_format($this->calculatePayoutForAffiliate($affiliate), 2);

        if ($payoutAmount > 0) {
            // Prepare payout data for PayPal
            $payoutData = [
                'recipient_type' => 'EMAIL',
                'receiver' => $affiliate->paypal_email, // Assuming the affiliate's PayPal email is the same as the user email
                'amount' => [
                    'value' => $payoutAmount,
                    'currency' => 'USD' // Or the currency of your choice
                ],
                'note' => 'Affiliate payout',
                'sender_item_id' => uniqid() // Unique identifier for tracking the payout
            ];

            // Send payout via PayPal
            $result = $this->paypalService->createPayout($payoutData);

            if ($result['success']) {
                $payment = new Payment();
                $payment->affiliate_id = $affiliate->id;
                $payment->amount = $payoutAmount;
                $payment->paid_at = now();
                $payment->paypal_transaction_id = $result['details']['batch_header']['payout_batch_id']; // Storing PayPal transaction ID
                $payment->payout_status = $result['details']['batch_header']['batch_status'];
                $payment->save();

                return $payment;
            } else {
                // Handle failure in PayPal payout
                // Log error details: $result['error']
                \Log::error('Failed to pay affiliate.');
            }
        }

        return null;
    }

    /**
     * Update payment status based on event data from PayPal.
     *
     * @param array $eventData Event data from PayPal webhook.
     * @param string $status New status to update in the database.
     * @return void
     */
    public function updatePaymentStatus($eventData, $status)
    {
        $transactionId = $eventData['batch_header']['payout_batch_id'];
        $payment = Payment::where('paypal_transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->payout_status = $status;
            $payment->save();
        }

        // Dispatch event for payment status updated
        UpdatedPaymentStatus::dispatch($payment);

    }

    /**
     * Calculate the payout amount for a specific affiliate.
     *
     * @param Affiliate $affiliate
     * @return float
     */
    public function calculatePayoutForAffiliate(Affiliate $affiliate)
    {
        $totalEarnings = $affiliate->unpaidEarnings();

        return $totalEarnings;
    }

    /**
     * Process payments for all affiliates.
     * This method can be used to batch process payments.
     *
     * @return void
     */
    public function processAllPayments()
    {
        $affiliates = Affiliate::approved()->get(); 

        foreach ($affiliates as $affiliate) {
            $this->processPaymentForAffiliate($affiliate);
        }
    }
}
