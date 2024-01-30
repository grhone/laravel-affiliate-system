{{-- Payments Create View --}}

<x-admin-layout>

    <h1>Create New Payment</h1>

    <form method="POST" action="{{ route('payments.store') }}">
        @csrf

        <div>
            <label>Affiliate:</label>
            <select name="affiliate_id">
                @foreach ($affiliates as $affiliate)
                    <option value="{{ $affiliate->id }}">{{ $affiliate->first_name }} {{ $affiliate->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Amount:</label>
            <input type="text" name="amount">
        </div>

        <button type="submit">Create Payment</button>
    </form>

</x-admin-layout>
