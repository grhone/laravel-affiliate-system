<x-mail::message>
    # Affiliate Registration

    <p>Hello {{ $user->name }},</p>
    <p>Your affiliate account has been been created.</p>
    <p>When your account has been approved, you'll recieve another email with further instructions.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</x-mail::message>