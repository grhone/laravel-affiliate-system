<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Referral;

class TrackingController extends Controller
{
    /**
     * Track affiliate link clicks.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function trackClick(Request $request)
    {
        // Assumes 'ref' is the query parameter for the affiliate referral code
        $referralCode = $request->query('ref');
        if ($referralCode) {
            $affiliate = Affiliate::where('referral_code', $referralCode)->first();

            if ($affiliate) {
                // Logic to record the click
                // Example: Create a new click record associated with the affiliate
                // You might also want to store more information like IP address, user agent, etc.
            }
        }

        // Redirect to the intended page or a default page
        return redirect()->to($request->get('redirect', '/'));
    }

    /**
     * Track conversions/sales.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function trackConversion(Request $request)
    {
        // This method would be called when a sale or conversion happens
        // You might want to pass relevant data like the affiliate's referral code,
        // the user who made the purchase, the purchase amount, etc.

        $referralCode = $request->input('referral_code');
        $userId = $request->input('user_id');
        $amount = $request->input('amount');

        if ($referralCode && $userId) {
            $affiliate = Affiliate::where('referral_code', $referralCode)->first();

            if ($affiliate) {
                // Logic to record the conversion
                // Example: Create a new referral record associated with the affiliate
                $referral = new Referral();
                $referral->affiliate_id = $affiliate->id;
                $referral->referred_user_id = $userId;
                $referral->conversion = true;
                $referral->amount = $amount;
                $referral->save();
            }
        }

        // Handle response (e.g., redirect, JSON response, etc.)
        return response()->json(['success' => true]);
    }
}
