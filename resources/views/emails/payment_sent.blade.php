<x-mail::message>
    # Thank You for Your Business

    <p>Hello {{ $user->name }},</p>
    <p>We have sent a payment of ${{ number_format($amount, 2) }} to your account. Thank you for being a valued member of our network.</p>
    <p>Best Regards,<br>Your Company</p>
</x-mail::message>
