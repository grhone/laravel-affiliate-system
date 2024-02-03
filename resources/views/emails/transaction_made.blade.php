<x-mail::message>
    # New Transaction

    Hello {{ $affiliate->first_name }},

    One of your referrals has made a transaction.

    <x-mail::panel>
    Transaction Amount: {{ config('affiliate.currency') }}{{ number_format($transaction->purchase_amount, 2) }}

    Commission/Earnings: {{ config('affiliate.currency') }}{{ number_format($transaction->earnings, 2) }}
    </x-mail::panel>

    Thank you for being a valued member of our network.

    Best Regards,
    {{ config('app.name') }}
</x-mail::message>
