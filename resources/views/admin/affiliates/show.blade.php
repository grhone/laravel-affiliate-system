{{-- Show View --}}

<x-admin-layout>

    <div class="mb-6">
        <a href="{{ route('admin.manage_affiliates') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-4 inline">
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
            </svg>
            Back
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">Affiliate Details</h1>

    <a href="{{ route('admin.affiliate.edit', $affiliate->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a>

    <div>
        <h2 class="text-xl font-bold mb-6">{{ $affiliate->first_name }} {{ $affiliate->last_name }}</h2>
        <p><span class="font-semibold">Approved:</span> {{ $affiliate->approved ? 'Yes' : 'No' }}</p>
        <p><span class="font-semibold">Email:</span> {{ $affiliate->user->email }}</p>
        <p><span class="font-semibold">Website:</span> {{ $affiliate->website ?? 'N/A' }}</p>
        <p><span class="font-semibold">Company Name:</span> {{ $affiliate->company_name ?? 'N/A' }}</p>
        <p><span class="font-semibold">Referral Code:</span> {{ $affiliate->referral_code }}</p>
        <p><span class="font-semibold">Commission Rate:</span> {{ $affiliate->commissionRate() * 100 }}% </p>
        <p><span class="font-semibold">Unpaid Earnings:</span> ${{ number_format($affiliate->unpaidEarnings(), 2) }}</p>

        <h2 class="text-xl font-bold my-6">Address</h2>
        <p><span class="font-semibold">Street Name:</span> {{ $affiliate->street_name }}</p>
        <p><span class="font-semibold">City:</span> {{ $affiliate->city }}</p>
        <p><span class="font-semibold">Country:</span> {{ $affiliate->country }}</p>
        <p><span class="font-semibold">State:</span> {{ $affiliate->state }}</p>
        <p><span class="font-semibold">Zip Code:</span> {{ $affiliate->zipcode }}</p>
        <p><span class="font-semibold">Phone Number:</span> {{ $affiliate->phone_number ?? 'N/A' }}</p>
        <p><span class="font-semibold">VAT Number:</span> {{ $affiliate->vat_number ?? 'N/A' }}</p>

        <h2 class="text-xl font-bold my-6">Payout Information</h2>
        <p><span class="font-semibold">Minimum Payout:</span> ${{ number_format($affiliate->minimum_payout, 2) }}</p>
        <p><span class="font-semibold">Payout Method:</span> {{ ucfirst($affiliate->payout_method) }}</p>
        <p><span class="font-semibold">PayPal Email:</span> {{ $affiliate->paypal_email ?? 'N/A' }}</p>
    </div>

    <div class="mb-6">
        @if($referrals->count() > 0)
            <h2 class="text-xl font-bold mb-6">Referred Users</h2>
            <table class="table-auto w-full">
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Date Created</th>
                @foreach($referrals as $referral)
                <tr>
                    <td class="text-center">{{ $referral->referredUser->name }}</td>
                    <td class="text-center">{{ $referral->referredUser->email }}</td>
                    <td class="text-center">{{ $referral->created_at->format('M, d Y g:i:s A') }}</td>
                </tr>

                @endforeach
            </table>

            <!-- Conditionally Display Pagination Controls -->
            @if($referrals->hasPages())
                {{ $referrals->links() }}
            @endif
        @else 
            <p>No referrals to show.</p>
        @endif
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-bold mb-6">Referred Transactions</h2>
        @if($transactions->count() > 0)
            <table class="table-auto w-full">
                <tr>
                    <th>User</th>
                    <th>Purchase Amount</th>
                    <th>Earnings</th>
                    <th>Transaction Type</th>
                    <th>Date</th>
                @foreach($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ $transaction->referral->referredUser->name }}</td>
                    <td class="text-center">{{ $transaction->purchase_amount }}</td>
                    <td class="text-center">{{ $transaction->earnings }}</td>
                    <td class="text-center">{{ $transaction->type }}</td>
                    <td class="text-center">{{ $transaction->created_at->format('M, d Y g:i:s A') }}</td>
                </tr>

                @endforeach
            </table>
        @else 
            <p>No transactions to show.</p>
        @endif
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-bold mb-6">Referral Clicks</h2>
        @if($clicks->count() > 0)
            <table class="table-auto w-full">
                <tr>
                    <th>IP Address</th>
                    <th>Referring URL</th>
                    <th>Date Created</th>
                @foreach($clicks as $click)
                <tr>
                    <td class="text-center">{{ $click->ip }}</td>
                    <td class="text-center">{{ $click->referring_url }}</td>
                    <td class="text-center">{{ $click->created_at->format('M, d Y g:i:s A') }}</td>
                </tr>

                @endforeach
            </table>

            <!-- Conditionally Display Pagination Controls -->
            @if($clicks->hasPages())
                {{ $clicks->links() }}
            @endif
        @else 
            <p>No clicks to show.</p>
        @endif
    </div>

</x-admin-layout>
