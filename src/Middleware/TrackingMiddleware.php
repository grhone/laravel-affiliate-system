<?php

namespace Grhone\LaravelAffiliateSystem\Middleware;

use Closure;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\ReferralClick;

class TrackingMiddleware
{
    /**
     * Handle an incoming request.
     * Check if the user is a registered affiliate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has(config('affiliate.url_parameter'))) {
            $referralCode = $request->query(config('affiliate.url_parameter'));
            
            // Find the affiliate associated with the referral code
            $affiliate = Affiliate::where('referral_code', $referralCode)->first();
            
            if ($affiliate) {
                // Save the click information and get the created click instance
                $click = ReferralClick::create([
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
    }
}
