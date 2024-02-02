<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Payout;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\PayoutItem;
use PayPal\Exception\PayPalConnectionException;

class PayPalService
{
    /**
     * PayPal API Context
     */
    protected $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('affiliate.paypal.client_id'),
                config('affiliate.paypal.secret')
            )
        );

        $this->apiContext->setConfig(config('affiliate.paypal.settings'));
    }

    /**
     * Create and send a payout to an affiliate.
     *
     * @param array $payoutData
     * @return array
     */
    public function createPayout($payoutData)
    {
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
                          ->setEmailSubject("You have an affiliate payout!");

        $payout = new Payout();
        $payout->setSenderBatchHeader($senderBatchHeader);

        $senderItem = new PayoutItem();
        $senderItem->setRecipientType($payoutData['recipient_type'])
                    ->setReceiver($payoutData['receiver'])
                    ->setAmount(new Currency(json_encode($payoutData['amount'])))
                    ->setNote($payoutData['note'])
                    ->setSenderItemId($payoutData['sender_item_id']);
        $payout->addItem($senderItem);

        try {
            $payout->create(null, $this->apiContext);
            return ['success' => true, 'details' => $payout];
        } catch (PayPalConnectionException $ex) {
            return ['success' => false, 'error' => json_decode($ex->getData())];
        }
    }

    // Additional methods for handling PayPal responses, errors, etc.
}
