<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Laravel\Cashier\Events\SubscriptionRenewed;
use Grhone\LaravelAffiliateSystem\Services\TransactionService;

class HandleSubscriptionRenewed
{

    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle the event.
     *
     * @param SubscriptionRenewed $event
     * @return void
     */
    public function handle(SubscriptionRenewed $event)
    {
        $transactionAmount = $transactionService->getTransactionAmountFromSubscription($event->subscription);
        $transactionService->handleSubscriptionEvent($event->user, $transactionAmount);
    }
}
