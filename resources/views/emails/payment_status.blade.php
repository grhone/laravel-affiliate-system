<x-mail::message>
    # Thank You for Your Business

    <p>Hello {{ $affiliate->user->first_name }},</p>
    <p>Your payment status has been updated.</p>

    <x-mail::panel>
    <p>Payment Amount: {{ number_format($amount, 2) }}</p>
    <p>Status: {{ ucfirst($status) }}</p>
    </x-mail::panel>

    <p>Thank you for being a valued member of our network.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</x-mail::message>
