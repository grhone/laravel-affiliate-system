<?php

namespace Grhone\LaravelAffiliateSystem\Providers;

use Illuminate\Support\ServiceProvider;

class AffiliateServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package's configuration file with the application's configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/referral.php', 'referral');
    }

    /**
     * Bootstrap package services.
     *
     * @return void
     */
    public function boot()
    {
        // Load package migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        if ($this->app->runningInConsole()) {
            // Publish package's configuration file
            $this->publishes([
                __DIR__.'/../../config/referral.php' => config_path('referral.php'),
            ], 'laravel-affiliate-config');

            // Publish package's migration files
            $this->publishes([
                __DIR__.'/../../database/migrations' => database_path('migrations'),
            ], 'laravel-affiliate-migrations');
        }

        // Load package's routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // Bind the ReferralController to the application container
        $this->app->bind('Grhone\LaravelAffiliateSystem\Controllers\AffiliateController', function ($app) {
            return new \Grhone\LaravelAffiliateSystem\Controllers\AffiliateController();
        });
    }
}
