<?php

return [

    'middleware' => [
        'affiliate' => ['auth'], // Example of default affiliate route middleware. Could include 'verified', etc.
        'admin' => ['auth'], // Add your admin middleware. Could use spatie/laravel-permission's 'role:admin|super-admin'.    
        'payment' => [], // Any additional middleware in addition to the admin middleware. Only needed if you have some specific admin role that will need to process payments.    
    ],

    /*
     * Affiliate referral code settings
     */
    'referral_code' => [
        'length' => 10, // Length of the generated referral code
    ],

    /*
     * Commission settings for affiliates
     */
    'commissions' => [
        'default_rate' => 0.10, // Default commission rate (e.g., 10%)
    ],

    /*
     * Cookie settings for tracking affiliate referrals
     */
    'cookie' => [
        'name' => 'affiliate_referral',
        'duration' => 365, // Cookie duration in days
    ],

    /*
     * Payment settings for affiliates
     */
    'payments' => [
        'minimum_payout' => 50.00, // Minimum amount required for payout
        'payout_period' => 'monthly', // Payout frequency - 'monthly', 'weekly', etc.
    ],

    /*
     * PayPal API Configuration
     */
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', 'your-client-id'),
        'secret' => env('PAYPAL_SECRET', 'your-secret'),
        'settings' => [
            'mode' => env('PAYPAL_MODE', 'sandbox'), // Can be 'sandbox' or 'live'
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'ERROR' // Can be 'FINE', 'INFO', 'WARN', or 'ERROR'
        ],
    ],

    /*
     * Affiliate URL parameter settings
     * 
     *  DON'T CHANGE THIS ONCE IT'S BEEN SET. 
     *  Changing this will invalidate all of your affiliates' existing links.
     */
    'url_parameter' => 'a_aid', // The parameter in the URL used to identify the affiliate ID. Choose something that won't be used anywhere else in the program. 

    /*
     * Currency parameter settings
     * 
     */
    'currency' => '$', // Used to display the sales and commissions. Assumes that the entire project is in the same currency.

];
