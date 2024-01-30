{{-- Show View --}}

<x-admin-layout>

    <h1>Affiliate Details</h1>

    <div>
        <h2>{{ $affiliate->first_name }} {{ $affiliate->last_name }}</h2>
        <p>Email: {{ $affiliate->user->email }}</p>
        <p>Earnings: {{ $affiliate->earnings }}</p>
        {{-- Add more details as needed --}}
    </div>

</x-admin-layout>
