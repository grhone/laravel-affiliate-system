<?php

namespace Grhone\LaravelAffiliateSystem\Middleware;

use Closure;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Illuminate\Support\Facades\Auth;

class AffiliateMiddleware
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
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();
        
        // Check if the user is an affiliate
        $isAffiliate = Affiliate::where('user_id', $user->id)->exists();

        if (!$isAffiliate) {
            // Optionally, you could redirect to a different page or show an error message
            abort(403, 'Unauthorized access - you must be an affiliate to view this page.');
        }

        return $next($request);
    }
}
