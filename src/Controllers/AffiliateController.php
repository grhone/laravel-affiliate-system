<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Grhone\LaravelAffiliateSystem\Http\Requests\StoreAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Http\Requests\UpdateAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\AffiliateSetting;
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

        $affiliate = Affiliate::with(['clicks', 'referredTransactions', 'referrals', 'payments'])
                            ->where('user_id', $affiliateId)
                            ->firstOrFail();

        $totalEarnings = $affiliate->earnings;
        $unpaidEarnings = $affiliate->unpaidEarnings();

        // Calculate Raw Clicks
        $rawClicksToday = $affiliate->clicks()->whereDate('referral_clicks.created_at', now()->toDateString())->count();
        $rawClicksThisMonth = $affiliate->clicks()->whereMonth('referral_clicks.created_at', now()->month)
                                                ->whereYear('referral_clicks.created_at', now()->year)->count();

        // Assuming you have a way to distinguish unique clicks, for example by IP or session ID
        // This is a placeholder for the logic you might use
        $uniqueClicksToday = $affiliate->clicks()->whereDate('referral_clicks.created_at', now()->toDateString())->distinct('ip')->count();
        $uniqueClicksThisMonth = $affiliate->clicks()->whereMonth('referral_clicks.created_at', now()->month)
                                                    ->whereYear('referral_clicks.created_at', now()->year)->distinct('ip')->count();

        // Signups - Assuming a referral that resulted in a user creation
        $signupsToday = $affiliate->referrals()->whereDate('referrals.created_at', now()->toDateString())->count();
        $signupsThisMonth = $affiliate->referrals()->whereMonth('referrals.created_at', now()->month)
                                                ->whereYear('referrals.created_at', now()->year)->count();

        // Sales/Transactions - Assuming a transaction is marked with a 'sale' type
        $salesToday = $affiliate->referredTransactions()->where('type', 'sale')
                                                        ->whereDate('referred_transactions.created_at', now()->toDateString())->count();
        $salesThisMonth = $affiliate->referredTransactions()->where('type', 'sale')
                                                            ->whereMonth('referred_transactions.created_at', now()->month)
                                                            ->whereYear('referred_transactions.created_at', now()->year)->count();

        // Transaction Value
        $transactionValueToday = $affiliate->referredTransactions()->where('type', 'sale')
                                                                ->whereDate('referred_transactions.created_at', now()->toDateString())
                                                                ->sum('purchase_amount');
        $transactionValueThisMonth = $affiliate->referredTransactions()->where('type', 'sale')
                                                                    ->whereMonth('referred_transactions.created_at', now()->month)
                                                                    ->whereYear('referred_transactions.created_at', now()->year)
                                                                    ->sum('purchase_amount');

        // Refunds
        $refundsToday = $affiliate->referredTransactions()->where('type', 'refund')
                                                        ->whereDate('referred_transactions.created_at', now()->toDateString())->count();
        $refundsThisMonth = $affiliate->referredTransactions()->where('type', 'refund')
                                                            ->whereMonth('referred_transactions.created_at', now()->month)
                                                            ->whereYear('referred_transactions.created_at', now()->year)->count();

        // TODO: FIGURE OUT HOW TO DEAL WITH CHARGEBACKS
        // // Chargebacks
        // $chargebacksToday = $affiliate->referredTransactions()->where('type', 'chargeback')
        //                                                     ->whereDate('referred_transactions.created_at', now()->toDateString())->count();
        // $chargebacksThisMonth = $affiliate->referredTransactions()->where('type', 'chargeback')
        //                                                         ->whereMonth('referred_transactions.created_at', now()->month)
        //                                                         ->whereYear('referred_transactions.created_at', now()->year)->count();

        // Commission/Earnings
        $commissionEarningsToday = $affiliate->referredTransactions()->whereDate('referred_transactions.created_at', now()->toDateString())
                                                                    ->sum('earnings');
        $commissionEarningsThisMonth = $affiliate->referredTransactions()->whereMonth('referred_transactions.created_at', now()->month)
                                                                        ->whereYear('referred_transactions.created_at', now()->year)
                                                                        ->sum('earnings');

        return view('laravel-affiliate-system::affiliates.dashboard', [
            'affiliate'         =>  $affiliate,
            'totalEarnings'     =>  $totalEarnings,
            'unpaidEarnings'    =>  $unpaidEarnings,
            'rawClicksToday' => $rawClicksToday,
            'rawClicksThisMonth' => $rawClicksThisMonth,
            'uniqueClicksToday' => $uniqueClicksToday,
            'uniqueClicksThisMonth' => $uniqueClicksThisMonth,
            'signupsToday' => $signupsToday,
            'signupsThisMonth' => $signupsThisMonth,
            'salesToday' => $salesToday,
            'salesThisMonth' => $salesThisMonth,
            'transactionValueToday' => $transactionValueToday,
            'transactionValueThisMonth' => $transactionValueThisMonth,
            'refundsToday' => $refundsToday,
            'refundsThisMonth' => $refundsThisMonth,
            // 'chargebacksToday' => $chargebacksToday,
            // 'chargebacksThisMonth' => $chargebacksThisMonth,
            'commissionEarningsToday' => $commissionEarningsToday,
            'commissionEarningsThisMonth' => $commissionEarningsThisMonth,
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
            return back()->withErrors($e->getMessage());
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

        return view('laravel-affiliate-system::affiliates.settings', compact('affiliate'));
    }

    public function updateSettings(Request $request)
    {
        $affiliate = Affiliate::findOrFail($request->user()->id);

        // Define all the settings you're expecting
        $allSettings = [
            'notification_new_sales' => 0,
            'notification_daily_report' => 0,
            'notification_weekly_report' => 0,
            'notification_monthly_report' => 0,
        ];

        // Override the default value if the setting is present in the request
        $requestSettings = $request->only(array_keys($allSettings));
        $settingsToUpdate = array_merge($allSettings, $requestSettings);

        foreach ($settingsToUpdate as $key => $value) {
            // Update or create each setting
            AffiliateSetting::updateOrCreate(
                ['affiliate_id' => $affiliate->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }


}
