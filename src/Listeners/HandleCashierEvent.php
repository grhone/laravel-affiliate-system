<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Grhone\LaravelAffiliateSystem\Services\TransactionService;

class HandleCashierEvent
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
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'invoice.payment_succeeded') {
            dd($event);
            // Handle the incoming event...
            $transactionAmount = $transactionService->getTransactionAmountFromSubscription($event->subscription);
            $transactionService->handleSubscriptionEvent($event->user, $transactionAmount);
        }
    }
}
