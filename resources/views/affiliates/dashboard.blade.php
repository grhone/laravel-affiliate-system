<x-app-layout>
    <h1>Affiliate Dashboard</h1>

    <div>
        <h2>Welcome, {{ $affiliate->first_name }} {{ $affiliate->last_name }}</h2>

        @if($affiliate->approved)

        <div>
            <input id="affiliateLink" name="affiliateLink" value="{{ env('APP_URL') }}/?{{config('affiliate.url_parameter')}}={{ $affiliate->referral_code }}" />
        </div>

        <h3>Affiliate Details</h3>
        <p>Referred Users: {{ $affiliate->referrals()->count() }}</p>
        <p>Referred Transactions: {{ $affiliate->referredTransactions()->count() }}</p>
        <p>Commission Rate: {{ $affiliate->commissionRate() }}% </p>
        <p>Unpaid Earnings: ${{ number_format($affiliate->unpaidEarnings(), 2) }}</p>
        <p>Referral Code: {{ $affiliate->referral_code }}</p>

        {{-- Additional sections like account information, settings, etc. --}}

        @else 
        <p>Your application has not yet been approved.</p>
        @endif

        <a href="{{ route('affiliate.edit') }}">Edit Affiliate Account</a>        
        <a href="{{ route('affiliate.settings') }}">Settings</a>
        <a href="{{ route('affiliate.reports') }}">Reports</a>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Check if 'affiliateLink' exists before adding event listener
            var affiliateLink = document.getElementById('affiliateLink');
            if (affiliateLink) {
                affiliateLink.addEventListener('click', function() {

                    navigator.clipboard.writeText(affiliateLink.value)
                        .then(() => {
                            alert('Link copied to clipboard!');
                        })
                        .catch(err => {
                            console.error('Error copying link to clipboard', err);
                            alert('Failed to copy link');
                        });
                });
            }
                
        });
    </script>
</x-app-layout>
