<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\ReferralMade;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateAffiliateEarnings implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param ReferralMade $event
     * @return void
     */
    public function handle(ReferralMade $event)
    {
        $referral = $event->referral;
        $affiliate = $referral->affiliate;

        if ($referral->conversion) {
            // Assuming the referral has an 'earnings' attribute which holds the earning amount for this referral
            $affiliate->earnings += $referral->earnings;
            $affiliate->save();
        }

        // Additional logic can be added here if needed, such as triggering further events
        // or updating other related models or records.
    }
}
