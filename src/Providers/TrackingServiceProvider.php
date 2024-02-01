<?php

namespace Grhone\LaravelAffiliateSystem\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Grhone\LaravelAffiliateSystem\Middleware\TrackingMiddleware;

class TrackingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Kernel $kernel
     * @return void
     */
    public function boot(Kernel $kernel)
    {
        $kernel->appendMiddlewareToGroup('web', TrackingMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register any bindings or singletons related to tracking here
    }
}
