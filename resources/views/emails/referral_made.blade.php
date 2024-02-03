<x-mail::message>
    # New Referral

    Hello {{ $affiliate->first_name }},

    You have a new referral.
    Thank you for being a valued member of our network.

    Best Regards,<br>{{ config('app.name') }}
</x-mail::message>
