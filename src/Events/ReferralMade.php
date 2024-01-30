<?php

namespace Grhone\LaravelAffiliateSystem\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Referral;

class ReferralMade
{
    use Dispatchable, SerializesModels;

    public $referral;

    /**
     * Create a new event instance.
     *
     * @param Referral $referral
     * @return void
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }
}
