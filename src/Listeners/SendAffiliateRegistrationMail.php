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
        
        // Send an email to the user to let them know that their user account has been created
        Mail::to($affiliate->user->email)->send(new AffiliateRegistrationMail($affiliate));

        if(config('affiliate.email.admin') != null && config('affiliate.email.admin')) {
            // Send an email to the admin to let them know that a new user account has been created
            Mail::to(config('affiliate.email.admin'))->send(new AffiliateRegistrationMailAdmin($affiliate));
        }

    }
}
