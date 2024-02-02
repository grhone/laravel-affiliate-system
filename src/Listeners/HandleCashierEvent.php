<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Grhone\LaravelAffiliateSystem\Services\TransactionService;
use Illuminate\Support\Facades\Log;

class HandleCashierEvent
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function handle(WebhookReceived $event)
    {

        if ($event->payload['type'] === 'invoice.payment_succeeded') {
            // Assuming $event->payload['data']['object'] contains the transaction details
            $invoice = $event->payload['data']['object'];

            // Extract the amount paid from the invoice
            $transactionAmount = $invoice['amount_paid'] / 100; // Convert from cents to dollars

            // Assuming you have a way to identify the user from the invoice or subscription ID
            $subscriptionId = $invoice['subscription'];
            $user = $this->transactionService->getUserFromEvent($subscriptionId);

            // Handle the subscription event
            if ($user) {
                $this->transactionService->handleSubscriptionEvent($user, $transactionAmount);
            } else {
                Log::error("User not found for subscription ID: {$subscriptionId}");
            }
        }
    }
}