<x-mail::message>
    # Welcome to Our Affiliate Program!

    Hello {{ $affiliate->first_name }},

    Your affiliate account has been approved.

    You can visit our website to get your affiliate code.

    Thank you for being a valued member of our network.

    Best Regards,
    {{ config('app.name') }}
</x-mail::message>