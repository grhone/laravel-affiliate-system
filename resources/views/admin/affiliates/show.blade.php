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

    <h1>Affiliate Details</h1>

    <div>
        <h2>{{ $affiliate->first_name }} {{ $affiliate->last_name }}</h2>
        <p>Approved: {{ $affiliate->approved ? 'Yes' : 'No' }}</p>
        <p>Email: {{ $affiliate->user->email }}</p>
        <p>Website: {{ $affiliate->website ?? 'N/A' }}</p>
        <p>Company Name: {{ $affiliate->company_name ?? 'N/A' }}</p>
        <p>Street Name: {{ $affiliate->street_name }}</p>
        <p>City: {{ $affiliate->city }}</p>
        <p>Country: {{ $affiliate->country }}</p>
        <p>State: {{ $affiliate->state }}</p>
        <p>Zip Code: {{ $affiliate->zipcode }}</p>
        <p>Phone Number: {{ $affiliate->phone_number ?? 'N/A' }}</p>
        <p>VAT Number: {{ $affiliate->vat_number ?? 'N/A' }}</p>
        <p>Minimum Payout: ${{ number_format($affiliate->minimum_payout, 2) }}</p>
        <p>Payout Method: {{ ucfirst($affiliate->payout_method) }}</p>
        <p>PayPal Email: {{ $affiliate->paypal_email ?? 'N/A' }}</p>
        @if($affiliate->approved)
        <p>Referred Users: {{ $affiliate->referrals()->count() }}</p>
        <p>Referred Transactions: {{ $affiliate->referredTransactions()->count() }}</p>
        <p>Commission Rate: {{ $affiliate->commissionRate() * 100 }}% </p>
        <p>Unpaid Earnings: ${{ number_format($affiliate->unpaidEarnings(), 2) }}</p>
        <p>Referral Code: {{ $affiliate->referral_code }}</p>
        @endif
    </div>

    @if($affiliate->referrals()->count() > 0)
    <h2>Referred Users</h2>
    <ul>
        @foreach($affiliate->referrals() as $referral)
        <li>{{ $referredTransaction->created_at }}</li>
        @endforeach
    </ul>
    @endif

    @if($affiliate->referredTransactions()->count() > 0)
    <h2>Referred Transactions</h2>
    <ul>
        @foreach($affiliate->referredTransactions() as $referredTransaction)
        <li>{{ $referredTransaction->created_at }}</li>
        @endforeach
    </ul>
    @endif

</x-admin-layout>
