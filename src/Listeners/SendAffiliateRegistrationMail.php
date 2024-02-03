<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\AffiliateRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Grhone\LaravelAffiliateSystem\Mail\AffiliateRegistrationMail;

class SendAffiliateRegistrationMail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param AffiliateRegistered $event
     * @return void
     */
    public function handle(AffiliateRegistered $event)
    {
        $affiliate = $event->affiliate;
        
        // Sending the welcome email to the affiliate's email
        Mail::to($affiliate->user->email)->send(new AffiliateRegistrationMail($affiliate));
    }
}
