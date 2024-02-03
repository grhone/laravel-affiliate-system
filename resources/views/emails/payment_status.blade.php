<x-mail::message>
    # Thank You for Your Business

    Hello {{ $affiliate->first_name }},
    
    Your payment status has been updated.

    <x-mail::panel>
    Payment Amount: {{ number_format($amount, 2) }}

    Status: {{ ucfirst($status) }}
    </x-mail::panel>

    Thank you for being a valued member of our network.
    
    Best Regards,<br>{{ config('app.name') }}
</x-mail::message>
