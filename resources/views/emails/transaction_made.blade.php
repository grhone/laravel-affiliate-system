<x-mail::message>
    # New Transaction

    <p>Hello {{ $affiliate->user->first_name }},</p>
    <p>One of your referrals has made a transaction.</p>
    <p>Thank you for being a valued member of our network.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</x-mail::message>
