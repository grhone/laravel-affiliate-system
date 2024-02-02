<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Laravel\Cashier\Events\SubscriptionCreated;
use Grhone\LaravelAffiliateSystem\Services\TransactionService;

class HandleSubscriptionCreated
{

    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle the event.
     *
     * @param SubscriptionCreated $event
     * @return void
     */
    public function handle(SubscriptionCreated $event)
    {
        
        $transactionAmount = $transactionService->getTransactionAmountFromSubscription($event->subscription);
        $transactionService->handleSubscriptionEvent($event->user, $transactionAmount);
    }
}
