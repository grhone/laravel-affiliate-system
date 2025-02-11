<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Grhone\LaravelAffiliateSystem\Http\Requests\StoreAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Http\Requests\UpdateAffiliateRequest;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\AffiliateSetting;
use Grhone\LaravelAffiliateSystem\Services\AffiliateService;
use Grhone\LaravelAffiliateSystem\Events\AffiliateRegistered;
use Illuminate\Http\Request;
use Exception;
use Auth;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    protected $affiliateService;

    /**
     * Constructor for the AffiliateController.
     *
     * @param AffiliateService $affiliateService
     */
    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    /**
     * Displays the affiliate's dashboard with various statistics.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        // Sale Value
        $saleValueToday = $affiliate->referredTransactions()->where('type', 'sale')
                                    ->whereDate('referred_transactions.created_at', now()->toDateString())
                                    ->sum('purchase_amount');
        $saleValueThisMonth = $affiliate->referredTransactions()->where('type', 'sale')
                                    ->whereMonth('referred_transactions.created_at', now()->month)
                                    ->whereYear('referred_transactions.created_at', now()->year)
                                    ->sum('purchase_amount');

        // Refunds
        $refundValueToday = $affiliate->referredTransactions()->where('type', 'refund')
                                    ->whereDate('referred_transactions.created_at', now()->toDateString())
                                    ->sum('purchase_amount');
        $refundsValueThisMonth = $affiliate->referredTransactions()->where('type', 'refund')
                                    ->whereMonth('referred_transactions.created_at', now()->month)
                                    ->whereYear('referred_transactions.created_at', now()->year)
                                    ->sum('purchase_amount');

        // Chargebacks
        $chargebacksToday = $affiliate->referredTransactions()
            ->where('type', 'chargeback')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $chargebacksThisMonth = $affiliate->referredTransactions()
            ->where('type', 'chargeback')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

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
            'saleValueToday' => $saleValueToday,
            'saleValueThisMonth' => $saleValueThisMonth,
            'refundValueToday' => $refundValueToday,
            'refundsValueThisMonth' => $refundsValueThisMonth,
            'chargebacksToday' => $chargebacksToday,
            'chargebacksThisMonth' => $chargebacksThisMonth,
            'commissionEarningsToday' => $commissionEarningsToday,
            'commissionEarningsThisMonth' => $commissionEarningsThisMonth,
        ]);
    }

    /**
     * Lists all affiliates.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $affiliates = Affiliate::all();

        return view('laravel-affiliate-system::affiliates.index', compact('affiliates'));
    }

    /**
     * Shows the form to create a new affiliate.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('laravel-affiliate-system::affiliates.create');
    }

    /**
     * Stores a new affiliate in the database.
     *
     * @param StoreAffiliateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAffiliateRequest $request)
    {
        try {
            $affiliate = $this->affiliateService->registerAffiliate($request->validated());
            
            return redirect()->route('affiliate.dashboard')->with('success', 'Affiliate created successfully.');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Shows the form to edit an existing affiliate.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        // Assuming the affiliate ID to edit is the authenticated user's ID
        $affiliate = Affiliate::findOrFail($request->user()->id);
        return view('laravel-affiliate-system::affiliates.edit', compact('affiliate'));
    }

    /**
     * Updates an existing affiliate in the database.
     *
     * @param UpdateAffiliateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Shows the affiliate's reports page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reports()
    {
        // Implement the logic to show affiliate reports data
        return view('laravel-affiliate-system::affiliates.reports');
    }

    /**
     * Deletes an existing affiliate from the database.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Shows the form to edit an affiliate's settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings()
    {
        $affiliate = Affiliate::findOrFail(Auth::user()->id);

        return view('laravel-affiliate-system::affiliates.settings', compact('affiliate'));
    }

    /**
     * Updates an affiliate's settings in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Generates a report based on given parameters.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'nullable|in:sale,refund,chargeback',
            'group_by' => 'nullable|in:day,week,month'
        ]);
        
        // Fetch affiliate data if found or fails with a not found HTTP exception response.
        $affiliate = Affiliate::findOrFail(Auth::id());
        
        // Filter transactions based on request parameters.
        $query = $affiliate->referredTransactions()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date));

        // Group by day, week or month based on request parameter.
        $groupBy = $request->group_by ?? 'day';
        
        // Build select query for report data based on specified group by period:
        $reportData = $query->selectRaw(
            match($groupBy) {
                'week' => "DATE_FORMAT(created_at, '%v/%x') as period",
                'month' => "DATE_FORMAT(created_at, '%Y-%m') as period",
                default => "DATE(created_at) as period"
            },
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(purchase_amount) as sales'),
            DB::raw('SUM(earnings) as earnings')
        )->groupBy('period')->get();

        return view('laravel-affiliate-system::affiliates.reports', [
            'reportData' => $reportData,
            'filters' => $request->all()
        ]);
    }
}
