<x-mail::message>
    # New Transaction

    Hello {{ $affiliate->first_name }},

    One of your referrals has made a transaction.

    Thank you for being a valued member of our network.

    Best Regards,<br>
    {{ config('app.name') }}
</x-mail::message>
