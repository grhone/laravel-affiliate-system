{{-- Create View --}}

<x-app-layout>

    <h1>Create New Affiliate</h1>

    <form method="POST" action="{{ route('affiliates.store') }}">
        @csrf

        {{-- Add form fields for new affiliate --}}
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name">
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name">
        </div>
        {{-- Include other fields as necessary --}}

        <button type="submit">Create</button>
    </form>

</x-app-layout>
