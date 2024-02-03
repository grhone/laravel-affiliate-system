<x-mail::message>
    # Affiliate Registration

    Hello {{ $affiliate->first_name }},

    Your affiliate account has been been created.

    When your account has been approved, you'll recieve another email with further instructions.

    Best Regards,<br>
    {{ config('app.name') }}
</x-mail::message>