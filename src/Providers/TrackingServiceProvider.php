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
            if ($request->has(config('affiliate.url_parameter'))) {
                $referralCode = $request->query(config('affiliate.url_parameter'));
                
                // Find the affiliate associated with the referral code
                $affiliate = Affiliate::where('referral_code', $referralCode)->first();
                
                if ($affiliate) {
                    // Save the click information and get the created click instance
                    $click = Click::create([
                        'affiliate_id' => $affiliate->id,
                        'ip' => $request->ip(),
                        'referring_url' => $request->headers->get('referer'),
                    ]);
                    
                    // Retrieve the cookie duration from the config file
                    $cookieDuration = config('affiliate.cookie.duration') * 1440; // Convert days to minutes
                    cookie()->queue('affiliate_referral', $referralCode, $cookieDuration);
                    // Queue a new cookie for click_id
                    cookie()->queue(cookie('click_id', $click->id, $cookieDuration));
                }
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
