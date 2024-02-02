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

        $payouts = new Payout();
        $payouts->setSenderBatchHeader($senderBatchHeader);

        foreach ($payoutData as $data) {
            $senderItem = new PayoutItem();
            $senderItem->setRecipientType($data['recipient_type'])
                       ->setReceiver($data['receiver'])
                       ->setAmount(new Currency(json_encode($data['amount'])))
                       ->setNote($data['note'])
                       ->setSenderItemId($data['sender_item_id']);
            $payouts->addItem($senderItem);
        }

        try {
            $payouts->create(null, $this->apiContext);
            return ['success' => true, 'details' => $payouts];
        } catch (PayPalConnectionException $ex) {
            return ['success' => false, 'error' => json_decode($ex->getData())];
        }
    }

    // Additional methods for handling PayPal responses, errors, etc.
}
