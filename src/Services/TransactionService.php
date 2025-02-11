<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Referral;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction;
use App\Models\User;
use Grhone\LaravelAffiliateSystem\Events\TransactionMade;

class TransactionService
{

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
    public function handleSubscriptionEvent($user, $type, $transactionAmount)
    {
        // Check if the user has an existing referral
        $referral = Referral::where('referred_user_id', $user->id)->first();

        if ($referral) {
            // Calculate earnings for this referral based on the transaction amount
            $earnings = $this->calculateEarningsForReferral($referral, $transactionAmount);

            // For chargebacks, reverse the earnings
            if ($type === 'chargeback') {
                $earnings = -abs($earnings); // Force negative value
            }

            // Create a new referred transaction
            $referredTransaction = new ReferredTransaction();
            $referredTransaction->referral_id = $referral->id;
            $referredTransaction->purchase_amount = $transactionAmount;
            $referredTransaction->type = $type;
            $referredTransaction->earnings = $earnings;
            $referredTransaction->save();

            // Dispatch an event to notify other parts of the system about the new referred transaction.
            TransactionMade::dispatch($referredTransaction);
        }
    }

    /**
     * Calculate earnings for a referral based on the transaction amount.
     *
     * @param Referral $referral The referral object.
     * @param float $transactionAmount The transaction amount.
     * @return float The earnings for this referral.
     */
    protected function calculateEarningsForReferral(Referral $referral, $transactionAmount)
    {
        // Get the affiliate associated with this referral
        $affiliate = $referral->affiliate;

        // Get the commission rate for this affiliate
        $commissionRate = $affiliate->commissionRate();

        // Earnings are calculated as a percentage of the transaction amount
        return $transactionAmount * $commissionRate;

    }
}
