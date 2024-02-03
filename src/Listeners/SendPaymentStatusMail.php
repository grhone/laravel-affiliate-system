<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\UpdatedPaymentStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Grhone\LaravelAffiliateSystem\Mail\PaymentStatusMail;

class SendPaymentStatusMail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param UpdatedPaymentStatus $event
     * @return void
     */
    public function handle(UpdatedPaymentStatus $event)
    {
        $payment = $event->payment;
        
        // Sending the welcome email to the affiliate's email
        Mail::to($payment->affiliate->user->email)->send(new PaymentStatusMail($payment));
    }
}
