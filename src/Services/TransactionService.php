<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Referral;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction;
use App\Models\User;

class TransactionService
{

    public function __construct()
    {
    }

    /**
     * Extracts user information from a Stripe event payload.
     *
     * @param array $customerId String with the customer ID.
     * @return mixed The user associated with the transaction, or null if not found.
     */
    public function getUserFromStripeID($customerId)
    {
        if ($customerId) {
            return User::where('stripe_id', $customerId)->first();
        }

        return null;
    }

    /**
     * Handles the creation of a referred transaction based on a Stripe event.
     *
     * @param mixed $user The user associated with the subscription event.
     * @param float $transactionAmount The transaction amount in dollars.
     * @return void
     */
    public function handleSubscriptionEvent($user, $transactionAmount)
    {
        // Check if the user has an existing referral
        $referral = Referral::where('referred_user_id', $user->id)->first();

        if ($referral && $referral->conversion) {
            // Calculate earnings for this referral based on the transaction amount
            $earnings = $this->calculateEarningsForReferral($referral, $transactionAmount);

            // Create a new referred transaction
            $referredTransaction = new ReferredTransaction();
            $referredTransaction->referral_id = $referral->id;
            $referredTransaction->purchase_amount = $transactionAmount;
            $referredTransaction->earnings = $earnings;
            $referredTransaction->save();
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
