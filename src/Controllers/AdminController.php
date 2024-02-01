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

        // Calculate total unpaid earnings
        $totalUnpaidEarnings = 0.0;
        $approvedAffiliates = Affiliate::where('approved', true)->get();
        $totalApprovedAffiliates = $approvedAffiliates->count();

        foreach ($approvedAffiliates as $affiliate) {
            $unpaidEarnings = $affiliate->unpaidEarnings();
            $totalUnpaidEarnings += $unpaidEarnings;
        }

        return view('laravel-affiliate-system::admin.affiliates.dashboard', compact('totalAffiliates', 'totalApprovedAffiliates', 'pendingAffiliates', 'totalUnpaidEarnings'));
    }

    /**
     * Show a list of all affiliates to manage.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageAffiliates(Request $request)
    {
        $query = Affiliate::query();

        // Check for status filter
        if ($request->filled('status')) {
            $status = $request->input('status') === 'pending' ? false : true;
            $query->where('approved', $status);
        }
    
        // Check for search query (assuming you want to search by name or email)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                      // Assuming 'email' is part of the affiliated user's information
                      ->orWhereHas('user', function ($query) use ($searchTerm) {
                          $query->where('email', 'like', '%' . $searchTerm . '%');
                      });
            });
        }
    
        // Execute the query and get the results
        $affiliates = $query->get();

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

    /**
     * Display the specified affiliate.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $affiliate = Affiliate::findOrFail($id);

        // Paginate the data
        $referrals = $affiliate->referrals()->paginate(15, ['*'], 'referrals'); 
        $clicks = $affiliate->clicks()->paginate(15, ['*'], 'clicks'); 
        $transactions = $affiliate->referredTransactions()->paginate(15, ['*'], 'transactions'); 

        return view('laravel-affiliate-system::admin.affiliates.show', compact('affiliate', 'referrals', 'clicks', 'transactions'));
    }


    /**
     * Show the form for editing the specified affiliate.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        return view('laravel-affiliate-system::admin.affiliates.edit', compact('affiliate'));
    }


    /**
     * Update the specified affiliate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $affiliate = Affiliate::findOrFail($id);
        // Validate and update logic here
        $affiliate->update($request->all());

        return redirect()->route('admin.affilate.show', ['id' => $id])
                        ->with('success', 'Affiliate updated successfully.');
    }

    /**
     * Remove the specified affiliate from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliate->delete();

            return redirect()->route('admin.manage_affiliates')
                            ->with('success', 'Affiliate deleted successfully.');
        } catch (\Exception $e) {
            // Log the error and redirect with an error message
            return redirect()->route('admin.manage_affiliates')
                            ->withErrors('Failed to delete the affiliate.');
        }
    }



}
