{{-- Dashboard View --}}

<x-admin-layout>

    <div class="w-full">
        <h1 class="text-xl font-bold mb-6">Admin Affiliate Dashboard</h1>

        <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">

            <a href="{{ route('admin.manage_affiliates') }}">
                <div class="p-6 border flex flex-col min-w-0 break-words bg-white w-full mb-10 shadow-lg rounded">
                    <p class="text-lg font-bold">Total Affiliates</p>
                    <p>{{ $totalAffiliates ?? 'N/A' }}</p>
                </div>
            </a>

            <a href="{{ route('admin.payments.index') }}">
            <div class="p-6 border flex flex-col min-w-0 break-words bg-white w-full mb-10 shadow-lg rounded">
                <p class="text-lg font-bold">Total Unpaid Earnings</p>
                <p>{{ config('affiliate.currency') }}{{ number_format($totalUnpaidEarnings, 2) ?? '0.00' }}</p>
            </div>

            <a href="{{ route('admin.manage_affiliates', ['status' => 'pending']) }}">
                <div class="p-6 border flex flex-col min-w-0 break-words bg-white w-full mb-10 shadow-lg rounded">
                    <p class="text-lg font-bold">Pending Approvals</p>
                    <p>{{ $pendingAffiliates ?? '0' }}</p>
                </div>
            </a>

            {{-- Additional widgets can be added here --}}
            <!-- TODO: ADD LINK TO REPORTS -->
        </div>
    </div> 
</x-admin-layout>
