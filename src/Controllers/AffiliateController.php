<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Grhone\LaravelAffiliateSystem\Http\Requests\StoreAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Http\Requests\UpdateAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Services\AffiliateService;
use Illuminate\Http\Request;
use Exception;
use Auth;

class AffiliateController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    public function dashboard()
    {
        $affiliateId = Auth::id(); // Assuming the affiliate ID is the same as the user ID

        $affiliate = Affiliate::with(['referrals', 'payments'])
                            ->where('user_id', $affiliateId)
                            ->firstOrFail();

        $totalEarnings = $affiliate->earnings;
        $unpaidEarnings = $affiliate->unpaidEarnings();
        // Other calculations like total referrals, conversion rate, recent activities...

        return view('laravel-affiliate-system::affiliates.dashboard', [
            'affiliate'         =>  $affiliate,
            'totalEarnings'     =>  $totalEarnings,
            'unpaidEarnings'    =>  $unpaidEarnings,
            // Pass other necessary data to the view...
        ]);
    }

    public function index()
    {
        $affiliates = Affiliate::all();
        return view('laravel-affiliate-system::affiliates.index', compact('affiliates'));
    }

    public function create()
    {
        return view('laravel-affiliate-system::affiliates.create');
    }

    public function store(StoreAffiliateRequest $request)
    {
        try {
            $affiliate = $this->affiliateService->registerAffiliate($request->validated());
            return redirect()->route('affiliate.dashboard')->with('success', 'Affiliate created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to create affiliate.');
        }
    }

    public function edit(Request $request)
    {
        // Assuming the affiliate ID to edit is the authenticated user's ID
        $affiliate = Affiliate::findOrFail($request->user()->id);
        return view('laravel-affiliate-system::affiliates.edit', compact('affiliate'));
    }

    public function update(UpdateAffiliateRequest $request)
    {
        try {
            $affiliate = Affiliate::findOrFail($request->user()->id);
            $affiliate->update($request->validated());
            return redirect()->route('affiliate.dashboard')->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to update profile.');
        }
    }

    public function reports()
    {
        // Implement the logic to show affiliate reports data
        return view('laravel-affiliate-system::affiliates.reports');
    }

    public function destroy($id)
    {
        try {
            Affiliate::findOrFail($id)->delete();
            return redirect()->route('affiliates.index')
                             ->with('success', 'Affiliate deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to delete affiliate.');
        }
    }

    public function settings()
    {
        $affiliate = Affiliate::findOrFail(Auth::user()->id);

        return view('affiliates.settings', compact('affiliate'));
    }

    public function updateSettings(Request $request)
    {
        $affiliate = Affiliate::findOrFail($request->user()->id);

        // Assuming the request contains keys like 'notification_new_sales', 'notification_daily_report', etc.
        $settings = $request->only([
            'notification_new_sales',
            'notification_daily_report',
            'notification_weekly_report',
            'notification_monthly_report'
        ]);

        foreach ($settings as $key => $value) {
            // Update or create each setting
            AffiliateSetting::updateOrCreate(
                ['affiliate_id' => $id, 'key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }


}
