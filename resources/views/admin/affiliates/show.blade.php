{{-- Show View --}}

<x-admin-layout>

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
        <p>Commission Rate: {{ $affiliate->commissionRate() }}% </p>
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
