<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Referral;
use Grhone\LaravelAffiliateSystem\Models\Payment; 
use Auth;
use Exception;

class AffiliateService
{
    /**
     * Register a new affiliate.
     *
     * @param array $data
     * @return Affiliate
     */
    public function registerAffiliate(array $data)
    {

        $userId = Auth::user()->id;

        // Check if the user already has an affiliate account
        $existingAffiliate = Affiliate::where('user_id', $userId)->first();
        if ($existingAffiliate) {
            // Handle the case where the user already has an affiliate account
            throw new Exception('User already has an affiliate account.');
        }

        // If no existing affiliate account, proceed to register a new affiliate
        $data['user_id'] = $userId;

        $affiliate = new Affiliate();
        $affiliate->fill($data);
        $affiliate->save();

        // Additional logic (if any) after an affiliate is registered.
        // For example, sending a welcome email, initializing settings, etc.

        return $affiliate;
    }

    /**
     * Calculate earnings for an affiliate.
     *
     * @param Affiliate $affiliate
     * @return float
     */
    public function calculateEarnings(Affiliate $affiliate)
    {
        $earnings = 0.0;
        $commissionRate = $affiliate->commissionRate();

        foreach ($affiliate->referrals as $referral) {
            if ($referral->conversion) {
                $earnings += $referral->earnings * $commissionRate;
            }
        }

        return $earnings;
    }


    /**
     * Process payments for all affiliates.
     *
     * @return void
     */
    public function processAllPayments()
    {
        $affiliates = Affiliate::approved()->get();

        foreach ($affiliates as $affiliate) {
            $earnings = $this->calculateEarnings($affiliate);

            if ($earnings > 0 && $earnings >= $affiliate->minimum_payout) {
                // Implement logic to process payment through the desired payment method.
                // For instance, using PayPalService to process the payment.
                // Create a Payment record to track the transaction.

                // Example (pseudocode):
                // $paymentResult = $paypalService->createPayout(...);
                // if ($paymentResult['success']) {
                //     $this->recordPayment($affiliate, $earnings, $paymentResult['details']);
                // }
            }
        }
    }

    /**
     * Record a payment transaction in the database.
     * 
     * @param Affiliate $affiliate
     * @param float $amount
     * @param array $details
     * @return void
     */
    protected function recordPayment(Affiliate $affiliate, $amount, $details)
    {
        $payment = new Payment([
            'affiliate_id' => $affiliate->id,
            'amount' => $amount,
            'paypal_transaction_id' => $details['transaction_id'],
            // Other fields as per the Payment model
        ]);
        $payment->save();

        // Additional logic post-payment, like updating affiliate's balance, sending notifications, etc.
    }
}
