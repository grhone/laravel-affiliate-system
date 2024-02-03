<?php

namespace Grhone\LaravelAffiliateSystem\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction;

class TransactionMade
{
    use Dispatchable, SerializesModels;

    public $referredTransaction;

    /**
     * Create a new event instance.
     *
     * @param ReferredTransaction $referredTransaction
     * @return void
     */
    public function __construct(ReferredTransaction $referredTransaction)
    {
        $this->referredTransaction = $referredTransaction;
    }
}
