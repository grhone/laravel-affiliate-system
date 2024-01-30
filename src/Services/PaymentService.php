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

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function processPaymentForAffiliate(Affiliate $affiliate)
    {
        $payoutAmount = $this->calculatePayoutForAffiliate($affiliate);

        if ($payoutAmount > 0) {
            // Prepare payout data for PayPal
            $payoutData = [
                'recipient_type' => 'EMAIL',
                'receiver' => $affiliate->user->email, // Assuming the affiliate's PayPal email is the same as the user email
                'amount' => [
                    'value' => $payoutAmount,
                    'currency' => 'USD' // Or the currency of your choice
                ],
                'note' => 'Affiliate payout',
                'sender_item_id' => 'payout-' . time() // Unique identifier for tracking the payout
            ];

            // Send payout via PayPal
            $result = $this->paypalService->createPayout($payoutData);

            if ($result['success']) {
                $payment = new Payment();
                $payment->affiliate_id = $affiliate->id;
                $payment->amount = $payoutAmount;
                $payment->paid_at = now();
                $payment->transaction_id = $result['details']->batch_header->payout_batch_id; // Storing PayPal transaction ID
                $payment->save();

                // Additional logic like updating affiliate's balance, sending notifications, etc.

                Mail::to($affiliate->user->email)->send(new PaymentSentMail($affiliate->user, $payoutAmount));


                return $payment;
            } else {
                // Handle failure in PayPal payout
                // Log error details: $result['error']
            }
        }

        return null;
    }

    /**
     * Calculate the payout amount for a specific affiliate.
     *
     * @param Affiliate $affiliate
     * @return float
     */
    public function calculatePayoutForAffiliate(Affiliate $affiliate)
    {
        // Get the last payment made to the affiliate
        $lastPayment = Payment::where('affiliate_id', $affiliate->id)->latest('paid_at')->first();
        $lastPaymentDate = $lastPayment ? $lastPayment->paid_at : null;

        $totalEarnings = 0.0;

        // Sum up earnings from new referred transactions since the last payment
        $newTransactions = $affiliate->referredTransactions()
                                    ->where('created_at', '>', $lastPaymentDate)
                                    ->get();

        foreach ($newTransactions as $transaction) {
            $totalEarnings += $transaction->earnings;
        }

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

        DB::transaction(function () use ($affiliates) {
            foreach ($affiliates as $affiliate) {
                $this->processPaymentForAffiliate($affiliate);
            }
        });
    }
}
