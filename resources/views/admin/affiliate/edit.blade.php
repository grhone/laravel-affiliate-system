{{-- Edit View --}}

<x-app-layout>

    <h1>Edit Affiliate</h1>

    <form method="POST" action="{{ route('affiliates.update', $affiliate->id) }}">
        @csrf
        @method('PUT')

        {{-- Add form fields for affiliate's information --}}
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="{{ $affiliate->first_name }}">
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="{{ $affiliate->last_name }}">
        </div>
        {{-- Include other fields as necessary --}}

        <button type="submit">Update</button>
    </form>

</x-app-layout>
