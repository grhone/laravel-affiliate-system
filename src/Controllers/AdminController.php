<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with overall program stats.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Example: Fetching some stats for the dashboard
        $totalAffiliates = Affiliate::count();
        $pendingAffiliates = Affiliate::where('approved', false)->count();
        // Add more stats as needed
        // TODO: A referral stats.

        return view('laravel-affiliate-system::admin.affiliates.dashboard', compact('totalAffiliates', 'pendingAffiliates'));
    }

    /**
     * Show a list of all affiliates to manage.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageAffiliates()
    {
        $affiliates = Affiliate::all();
        return view('laravel-affiliate-system::admin.affiliates.manage_affiliates', compact('affiliates'));
    }

    /**
     * Approve an affiliate application.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function approveAffiliate($id)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliate->approved = true;
            $affiliate->save();

            return redirect()->route('admin.manage_affiliates')
                            ->with('success', 'Affiliate approved successfully.');
        } catch (\Exception $e) {
            // Log error and redirect with an error message
            return redirect()->route('admin.manage_affiliates')
                            ->withErrors('Affiliate approval failed.');
        }
    }

    /**
     * Deny an affiliate application.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function denyAffiliate($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        // Optionally, you might want to perform some cleanup or notify the affiliate

        $affiliate->delete();

        return redirect()->route('admin.manage_affiliates')
                         ->with('success', 'Affiliate application denied.');
    }
}
