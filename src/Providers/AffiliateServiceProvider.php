<?php

namespace Grhone\LaravelAffiliateSystem\Providers;

use Illuminate\Support\ServiceProvider;
use Grhone\LaravelAffiliateSystem\Services\AffiliateService;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;
use Grhone\LaravelAffiliateSystem\Services\PayPalService;
use Laravel\Cashier\Events\WebhookReceived;
use Grhone\LaravelAffiliateSystem\Events\AffiliateApproved;
use Grhone\LaravelAffiliateSystem\Events\AffiliateRegistered;
use Grhone\LaravelAffiliateSystem\Events\ReferralMade;
use Grhone\LaravelAffiliateSystem\Events\UpdatedPaymentStatus;
use Grhone\LaravelAffiliateSystem\Events\TransactionMade;
use Grhone\LaravelAffiliateSystem\Listeners\HandleCashierEvent;
use Grhone\LaravelAffiliateSystem\Listeners\SendAffiliateRegistrationMail;
use Grhone\LaravelAffiliateSystem\Listeners\SendAffiliateWelcomeMail;
use Grhone\LaravelAffiliateSystem\Listeners\SendReferralMadeMail;
use Grhone\LaravelAffiliateSystem\Listeners\SendPaymentStatusMail;
use Grhone\LaravelAffiliateSystem\Listeners\SendTransactionMadeMail;
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

        // Listen for events from Cashier 
        Event::listen(
            WebhookReceived::class,
            [HandleCashierEvent::class, 'handle']
        );

        Event::listen(
            AffiliateRegistered::class,
            [SendAffiliateRegistrationMail::class, 'handle']
        );

        Event::listen(
            AffiliateApproved::class,
            [SendAffiliatewelcomeMail::class, 'handle']
        );
        
        Event::listen(
            ReferralMade::class,
            [SendReferralMadeMail::class, 'handle']
        );
        
        Event::listen(
            UpdatedPaymentStatus::class,
            [SendPaymentStatusMail::class, 'handle']
        );
        
        Event::listen(
            TransactionMade::class,
            [SendTransactionMadeMail::class, 'handle']
        );

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

}
