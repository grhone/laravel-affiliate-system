<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\ReferralMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Grhone\LaravelAffiliateSystem\Mail\ReferralMadeMail;

class SendReferralMadeMail implements ShouldQueue
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
        
        // Sending the welcome email to the affiliate's email
        Mail::to($referral->affiliate->user->email)->send(new ReferralMadeMail($referral));
    }
}
