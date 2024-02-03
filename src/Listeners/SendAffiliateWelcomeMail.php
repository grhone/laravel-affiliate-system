<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\AffiliateApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Grhone\LaravelAffiliateSystem\Mail\AffiliateWelcomeMail;

class SendAffiliateWelcomeMail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param AffiliateApproved $event
     * @return void
     */
    public function handle(AffiliateApproved $event)
    {
        $affiliate = $event->affiliate;
        
        // Sending the welcome email to the affiliate's email
        Mail::to($affiliate->user->email)->send(new AffiliateWelcomeMail($affiliate));
    }
}
