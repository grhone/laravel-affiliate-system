<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\TransactionMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Grhone\LaravelAffiliateSystem\Mail\TransactionMadeMail;

class SendTransactionMadeMail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param TransactionMade $event
     * @return void
     */
    public function handle(TransactionMade $event)
    {
        $referredTransaction = $event->referredTransaction;
        
        // Sending the welcome email to the affiliate's email
        Mail::to($referredTransaction->referral->affiliate->user->email)->send(new TransactionMadeMail($referredTransaction));
    }
}
