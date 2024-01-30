{{-- Dashboard View --}}

<x-admin-layout>

    <h1>Affiliate Dashboard</h1>

    <div class="dashboard-widgets">
        {{-- Ensure you have the necessary data passed from your controller to populate these widgets --}}
        <div class="widget">
            <h3>Total Affiliates</h3>
            <p>{{ $totalAffiliates ?? 'N/A' }}</p>
        </div>

        <div class="widget">
            <h3>Total Earnings</h3>
            <p>${{ $totalEarnings ?? '0.00' }}</p>
        </div>

        <div class="widget">
            <h3>Pending Approvals</h3>
            <p>{{ $pendingAffiliates ?? '0' }}</p>
        </div>

        {{-- Additional widgets can be added here --}}
    </div>

</x-admin-layout>
