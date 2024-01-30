<?php

namespace Grhone\LaravelAffiliateSystem\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class TrackingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->pushMiddlewareToGroup('web', function ($request, $next) {
            if ($request->has('ref')) {
                $referralCode = $request->query('ref');
                // Retrieve the cookie duration from the config file
                $cookieDuration = config('affiliate.cookie.duration') * 1440; // Convert days to minutes
                cookie()->queue('affiliate_referral', $referralCode, $cookieDuration);
            }
            return $next($request);
        });
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
