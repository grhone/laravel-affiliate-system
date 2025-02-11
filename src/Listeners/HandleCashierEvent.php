<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Grhone\LaravelAffiliateSystem\Services\TransactionService;

class HandleCashierEvent
{
    private $transactionService;

    /**
     * Constructor for HandleCashierEvent class.
     * 
     * @param TransactionService $transactionService An instance of the transaction service to process transactions.
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle a Stripe webhook event.
     * 
     * This method is called when a new Stripe webhook event is received. Depending on the type of the event, it extracts transaction details and processes them accordingly.
     * 
     * @param WebhookReceived $event The webhook event that was received from Stripe.
     */
    public function handle(WebhookReceived $event)
    {

        $payload = $event->payload;

        // Check if the event is an invoice payment succeeded event.
        if ($payload['type'] === 'invoice.payment_succeeded') {

            // Extract the invoice object from the payload.
            $invoice = $event->payload['data']['object'];

            // Extract the amount paid from the invoice
            $transactionAmount = $invoice['amount_paid'] / 100; // Convert from cents to dollars

            $user = $this->transactionService->getUserFromStripeID($invoice['customer']);

            // Handle the subscription event
            if ($user) {
                $this->transactionService->handleSubscriptionEvent($user, 'sale', $transactionAmount);
            } else {
                Log::error("User not found.");
            }
        }

        // Check if the event is a charge refunded event.
        if ($payload['type'] === 'charge.refunded') {

            // Extract the invoice object from the payload.
            $invoice = $event->payload['data']['object'];

            // Extract the amount paid from the invoice
            $transactionAmount = $invoice['amount_refunded'] / 100; // Convert from cents to dollars
            $transactionAmount = -$transactionAmount; // Make the amount negative

            $user = $this->transactionService->getUserFromStripeID($invoice['customer']);

            // Handle the subscription event
            if ($user) {
                $this->transactionService->handleSubscriptionEvent($user, 'refund', $transactionAmount);
            } else {
                Log::error("User not found.");
            }
        }

        // Check if the event is a charge dispute created event.
        if ($payload['type'] === 'charge.dispute.created') {

            // Extract the charge object from the payload.
            $charge = $event->payload['data']['object'];
            
            // Extract the amount paid from the charge.
            $transactionAmount = $charge['amount'] / 100; // Convert from cents to dollars
            $transactionAmount = -$transactionAmount;
            
            $user = $this->transactionService->getUserFromStripeID($charge['customer']);
            
            if ($user) {
                $this->transactionService->handleSubscriptionEvent($user, 'chargeback', $transactionAmount);
            }
        }
    }
}