<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Referral;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction;
use Stripe\StripeClient;

class TransactionService
{

    public function __construct()
    {
    }

    protected function getTransactionAmountFromSubscription($subscription)
    {
        // Initialize Stripe Client
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // Retrieve the Stripe subscription object
        try {
            $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
            
            // Extract the transaction amount
            // Stripe stores amounts in cents, so you may need to convert this to dollars or your desired currency unit
            $transactionAmount = $stripeSubscription->plan->amount / 100; // Convert to dollars

            return $transactionAmount;
        } catch (\Exception $e) {
            // Handle any exceptions, such as API errors
            \Log::error("Stripe API error: " . $e->getMessage());
            return 0;
        }
    }

    protected function handleSubscriptionEvent($user, $transactionAmount)
    {
        // Check if the user has an existing referral
        $referral = Referral::where('referred_user_id', $user->id)->first();

        if ($referral && $referral->conversion) {
            // Calculate earnings for this referral based on the transaction amount
            $earnings = $this->calculateEarningsForReferral($referral, $transactionAmount);

            // Create a new referred transaction
            $referredTransaction = new ReferredTransaction();
            $referredTransaction->referral_id = $referral->id;
            $referredTransaction->referred_user_id = $user->id;
            $referredTransaction->earnings = $earnings;
            $referredTransaction->save();

            // Additional logic as needed
        }
    }

    protected function calculateEarningsForReferral(Referral $referral, $transactionAmount)
    {
        $affiliate = $referral->affiliate;
        $commissionRate = $affiliate->commissionRate();

        // Earnings are calculated as a percentage of the transaction amount
        return $transactionAmount * $commissionRate;

    }
}
