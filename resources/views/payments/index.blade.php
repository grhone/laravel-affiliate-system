{{-- Payments Index View --}}

<x-admin-layout>

    <h1>Payments List</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Affiliate ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Paid At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->affiliate_id }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->payout_status ?? 'Pending' }}</td>
                    <td>{{ $payment->paid_at }}</td>
                    <td>
                        <a href="{{ route('payments.show', $payment->id) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-admin-layout>
