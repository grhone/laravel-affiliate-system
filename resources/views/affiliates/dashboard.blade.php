<x-app-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold">Affiliate Dashboard</h1>
    </div>

    <div class="mb-6">
        <a href="{{ route('affiliate.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit Affiliate Account</a>        
        <a href="{{ route('affiliate.settings') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Settings</a>
        <a href="{{ route('affiliate.reports') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Reports</a>
    </div>

    <div class="mb-6">
        <p class="mb-6">Welcome, {{ $affiliate->first_name }} {{ $affiliate->last_name }}</p>

        @if($affiliate->approved)

        <div class="mb-6">
            <p>Affiliate Link:</p>
            <input id="affiliateLink" name="affiliateLink" value="{{ env('APP_URL') }}/?{{config('affiliate.url_parameter')}}={{ $affiliate->referral_code }}" />
        </div>

        <div>
            <h2 class="text-xl font-bold">Affiliate Details</h2>
            <p><span class="font-semibold">Referred Users:</span> {{ $affiliate->referrals()->count() }}</p>
            <p><span class="font-semibold">Referred Transactions:</span>  {{ $affiliate->referredTransactions()->count() }}</p>
            <p><span class="font-semibold">Commission Rate:</span>  {{ $affiliate->commissionRate() * 100 }}% </p>
            <p><span class="font-semibold">Unpaid Earnings:</span>  ${{ number_format($affiliate->unpaidEarnings(), 2) }}</p>
            <p><span class="font-semibold">Referral Code:</span>  {{ $affiliate->referral_code }}</p>
        </div>
        {{-- Additional sections like account information, settings, etc. --}}

        @else 
        <p>Your application has not yet been approved.</p>
        @endif

    </div>

    @if($affiliate->approved)
        <h2 class="text-xl font-bold">Clicks</h2>
        @if($affiliate->clicks()->count() > 0)
        <ul>
            @foreach($affiliate->clicks() as $click)
            <li>{{ $click->created_at }}</li>
            @endforeach
        </ul>
        @endif
    @endif

    @if($affiliate->referrals()->count() > 0)
    <h2 class="text-xl font-bold">Referred Users</h2>
    <ul>
        @foreach($affiliate->referrals() as $referral)
        <li>{{ $referredTransaction->created_at }}</li>
        @endforeach
    </ul>
    @endif

    @if($affiliate->referredTransactions()->count() > 0)
    <h2 class="text-xl font-bold">Referred Transactions</h2>
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
