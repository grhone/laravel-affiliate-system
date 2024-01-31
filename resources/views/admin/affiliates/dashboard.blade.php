{{-- Dashboard View --}}

<x-admin-layout>

    <h1>Admin Affiliate Dashboard</h1>

    <div class="dashboard-widgets">
        {{-- Ensure you have the necessary data passed from your controller to populate these widgets --}}
        <div class="widget">
            <h3>Total Approved Affiliates</h3>
            <p>{{ $totalApprovedAffiliates ?? 'N/A' }}</p>
        </div>

        <div class="widget">
            <h3>Total Unpaid Earnings</h3>
            <p>${{ $totalUnpaidEarnings ?? '0.00' }}</p>
        </div>

        <div class="widget">
            <h3><a href="{{ route('admin.manage_affiliates') }}">Pending Approvals</a></h3>
            <p>{{ $pendingAffiliates ?? '0' }}</p>
        </div>

        {{-- Additional widgets can be added here --}}
    </div>

</x-admin-layout>
