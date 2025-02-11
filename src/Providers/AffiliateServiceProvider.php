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

        // Listen for events from the application
        Event::listen(
            AffiliateRegistered::class,
            [SendAffiliateRegistrationMail::class, 'handle']
        );

        // Listen for events from the application
        Event::listen(
            AffiliateApproved::class,
            [SendAffiliatewelcomeMail::class, 'handle']
        );
        
        // Listen for events from the application
        Event::listen(
            ReferralMade::class,
            [SendReferralMadeMail::class, 'handle']
        );
        
        // Listen for events from the application
        Event::listen(
            UpdatedPaymentStatus::class,
            [SendPaymentStatusMail::class, 'handle']
        );
        
        // Listen for events from the application
        Event::listen(
            TransactionMade::class,
            [SendTransactionMadeMail::class, 'handle']
        );

        // Listen for events from the application
        Event::listen(
            AffiliateReportEvent::class,
            [SendAffiliateReportMail::class, 'handle']
        );

        // Send affiliate report email
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
    
            $schedule->call(function () {
                // Get all approved affiliates
                $affiliates = \Grhone\LaravelAffiliateSystem\Models\Affiliate::approved()->get();
                
                foreach ($affiliates as $affiliate) {
                    // Get the number of sales and earnings for the affiliate in the last month
                    $sales = $affiliate->referredTransactions()
                        ->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year)
                        ->count();

                    $earnings = $affiliate->referredTransactions()
                        ->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year)
                        ->sum('earnings');

                    // Dispatch the event with the affiliate's sales and earnings data
                    event(new \Grhone\LaravelAffiliateSystem\Events\AffiliateReportEvent(
                        $affiliate,
                        ['sales' => $sales, 'earnings' => $earnings]
                    ));
                }
            })->monthlyOn(1, '00:00'); // Run this task on the first day of every month at midnight
        });

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

        // Register PaymentService
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

    /**
     * Load the routes for the application.
     *
     * @return void
     */
    protected function loadRoutesWithMiddleware()
    {
        // Load routes with middleware from the package's directory.
        Route::group(['middleware' => config('affiliate.middleware.regular', ['web'])], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        });
    }    

}
