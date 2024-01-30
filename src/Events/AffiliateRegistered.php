<?php

namespace Grhone\LaravelAffiliateSystem\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateRegistered
{
    use Dispatchable, SerializesModels;

    public $affiliate;

    /**
     * Create a new event instance.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }
}
