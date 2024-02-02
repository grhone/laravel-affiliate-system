<?php

namespace Grhone\LaravelAffiliateSystem\Providers;

use Illuminate\Support\ServiceProvider;
use Grhone\LaravelAffiliateSystem\Services\AffiliateService;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;
use Grhone\LaravelAffiliateSystem\Services\PayPalService;
use Laravel\Cashier\Events\SubscriptionCreated;
use Laravel\Cashier\Events\SubscriptionRenewed;
use Grhone\LaravelAffiliateSystem\Listeners\HandleSubscriptionCreated;
use Grhone\LaravelAffiliateSystem\Listeners\HandleSubscriptionRenewed;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Referral;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;

class AffiliateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes, views, migrations, and publish assets
        $this->loadRoutesWithMiddleware();
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'laravel-affiliate-system');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/affiliate.php' => config_path('affiliate.php'),
            __DIR__.'/../../resources/views' => resource_path('views/vendor/laravel-affiliate-system'),
            // Additional files to publish...
        ]);

        Event::listen(
            SubscriptionCreated::class,
            [HandleSubscriptionCreated::class, 'handle']
        );

        Event::listen(
            SubscriptionRenewed::class,
            [HandleSubscriptionRenewed::class, 'handle']
        );

        // $this->app['events']->listen(SubscriptionCreated::class, function ($event) {
        //     $transactionAmount = $this->getTransactionAmountFromSubscription($event->subscription);
        //     $this->handleSubscriptionEvent($event->user, $transactionAmount);
        // });
        
        // $this->app['events']->listen(SubscriptionRenewed::class, function ($event) {
        //     $transactionAmount = $this->getTransactionAmountFromSubscription($event->subscription);
        //     $this->handleSubscriptionEvent($event->user, $transactionAmount);
        // });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register AffiliateService and PaymentService
        $this->app->singleton(AffiliateService::class, function ($app) {
            return new AffiliateService();
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });

        // Register PayPalService
        $this->app->singleton(PayPalService::class, function ($app) {
            return new PayPalService();
        });

        // Merge package configuration file with the application's copy
        $this->mergeConfigFrom(
            __DIR__.'/../../config/affiliate.php', 'affiliate'
        );
    }

    protected function loadRoutesWithMiddleware()
    {
        Route::group(['middleware' => config('affiliate.middleware.regular', ['web'])], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        });
    }


    protected function getTransactionAmountFromSubscription($subscription)
    {
        // Initialize Stripe Client
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        // Retrieve the Stripe subscription object
        try {
            $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
            
            // Extract the transaction amount
            // Stripe stores amounts in cents, so you may need to convert this to dollars or your desired currency unit
            $transactionAmount = $stripeSubscription->plan->amount / 100; // Convert to dollars

            return $transactionAmount;
        } catch (\Exception $e) {
            // Handle any exceptions, such as API errors
            \Log::error("Stripe API error: " . $e->getMessage());
            return 0;
        }
    }



    protected function handleSubscriptionEvent($user, $transactionAmount)
    {
        // Check if the user has an existing referral
        $referral = Referral::where('referred_user_id', $user->id)->first();

        if ($referral && $referral->conversion) {
            // Calculate earnings for this referral based on the transaction amount
            $earnings = $this->calculateEarningsForReferral($referral, $transactionAmount);

            // Create a new referred transaction
            $referredTransaction = new ReferredTransaction();
            $referredTransaction->referral_id = $referral->id;
            $referredTransaction->referred_user_id = $user->id;
            $referredTransaction->earnings = $earnings;
            $referredTransaction->save();

            // Additional logic as needed
        }
    }

    protected function calculateEarningsForReferral(Referral $referral, $transactionAmount)
    {
        $affiliate = $referral->affiliate;
        $commissionRate = $affiliate->commissionRate();

        // Earnings are calculated as a percentage of the transaction amount
        return $transactionAmount * $commissionRate;

    }

    

}
