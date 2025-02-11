<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PayPalService
{
    protected string $clientId;
    protected string $secret;
    protected string $apiBaseUrl;

    /**
     * Constructor to initialize the PayPal service.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->clientId = config('affiliate.paypal.client_id');
        $this->secret = config('affiliate.paypal.secret');
        
        $mode = config('affiliate.paypal.mode', 'sandbox'); // Default to sandbox if not set
        if ($mode === 'live') {
            $this->apiBaseUrl = 'https://api.paypal.com';
        } else {
            $this->apiBaseUrl = 'https://api.sandbox.paypal.com';
        }
    }

    /**
     * Get a new access token from PayPal.
     *
     * @return string|null
     */
    protected function getAccessToken(): ?string
    {
        $response = Http::withBasicAuth($this->clientId, $this->secret)
                        ->asForm()
                        ->post("{$this->apiBaseUrl}/v1/oauth2/token", [
                            'grant_type' => 'client_credentials',
                        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        return null;
    }

    /**
     * Create and send a payout to an affiliate.
     *
     * @param array $payoutData
     * @return array
     */
    public function createPayout(array $payoutData): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return ['success' => false, 'error' => 'Unable to retrieve access token'];
        }

        $payoutResponse = Http::withToken($accessToken)
                              ->withHeaders(['Content-Type' => 'application/json'])
                              ->post("{$this->apiBaseUrl}/v1/payments/payouts", [
                                  'sender_batch_header' => [
                                      'sender_batch_id' => uniqid(),
                                      'email_subject' => 'You have an affiliate payout!',
                                  ],
                                  'items' => [
                                      [
                                          'recipient_type' => $payoutData['recipient_type'],
                                          'receiver' => $payoutData['receiver'],
                                          'note' => $payoutData['note'],
                                          'sender_item_id' => $payoutData['sender_item_id'],
                                          'amount' => [
                                              'value' => $payoutData['amount']['value'],
                                              'currency' => $payoutData['amount']['currency'],
                                          ],
                                      ],
                                  ],
                              ]);

        if ($payoutResponse->successful()) {
            return ['success' => true, 'details' => $payoutResponse->json()];
        } else {
            return ['success' => false, 'error' => $payoutResponse->body()];
        }
    }
}
