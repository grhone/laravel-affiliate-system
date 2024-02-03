<?php

namespace Grhone\LaravelAffiliateSystem\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Payment;

class UpdatedPaymentStatus
{
    use Dispatchable, SerializesModels;

    public $referral;

    /**
     * Create a new event instance.
     *
     * @param Payment $referral
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
