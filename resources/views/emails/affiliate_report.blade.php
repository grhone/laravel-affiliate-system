<x-mail::message>
    # Monthly Affiliate Report

    Hello {{ $affiliate->first_name }},

    Here's your monthly affiliate performance summary:

    <x-mail::panel>
    Total Sales This Month: {{ $monthlySales }}<br>
    Total Earnings: {{ config('affiliate.currency') }}{{ number_format($monthlyEarnings, 2) }}
    </x-mail::panel>

    You can view detailed statistics in your affiliate dashboard.

    Thank you for partnering with us!

    Best Regards,<br>
    {{ config('app.name') }}
</x-mail::message> 