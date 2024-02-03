<x-mail::message>
    # New Referral

    <p>Hello {{ $user->name }},</p>
    <p>You have a new referral.</p>
    <p>Thank you for being a valued member of our network.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</x-mail::message>
