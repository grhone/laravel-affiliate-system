<x-mail::message>
    # Welcome to Our Affiliate Program!

    <p>Hello {{ $user->name }},</p>
    <p>Your affiliate account has been approved.</p>
    <p>You can visit our website to get your affiliate code.</p>
    <p>Thank you for being a valued member of our network.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</x-mail::message>