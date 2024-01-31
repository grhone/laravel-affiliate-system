<x-app-layout>
    <h1>Affiliate Dashboard</h1>

    <div>
        <h2>Welcome, {{ $affiliate->first_name }} {{ $affiliate->last_name }}</h2>

        @if($affiliate->approved)
        <p>Unpaid Earnings: ${{ number_format($unpaidEarnings, 2) }}</p>

        <p>Total Earnings: ${{ number_format($totalEarnings, 2) }}</p>
        
        {{-- Display other affiliate data --}}
        <div>
            <h3>Referral Statistics</h3>
            <p>Total Referrals: {{ count($affiliate->referrals) }}</p>
            {{-- Include other statistics like conversion rate, successful conversions... --}}
        </div>

        <div>
            <h3>Recent Activities</h3>
            {{-- Display recent referrals and payments --}}
        </div>

        {{-- Additional sections like account information, settings, etc. --}}

        @else 
        <p>Your application has not yet been approved.</p>
        @endif
    </div>
</x-app-layout>
