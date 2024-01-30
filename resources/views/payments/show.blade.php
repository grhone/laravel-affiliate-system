{{-- Payments Show View --}}

<x-admin-layout>

    <h1>Payment Details</h1>

    <div>
        <p>Affiliate ID: {{ $payment->affiliate_id }}</p>
        <p>Amount: {{ $payment->amount }}</p>
        <p>Status: {{ $payment->payout_status ?? 'Pending' }}</p>
        <p>Paid At: {{ $payment->paid_at }}</p>
        <p>PayPal Transaction ID: {{ $payment->paypal_transaction_id }}</p>
        {{-- Display other payment details as needed --}}
    </div>

</x-admin-layout>
