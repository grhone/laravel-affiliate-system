{{-- Edit View --}}

<x-app-layout>

    <div class="mb-6">
        <a href="{{ route('affiliate.dashboard') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-4 inline">
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
            </svg>
            Back
        </a>
    </div>

    <h1>Edit Affiliate</h1>

    <form method="POST" action="{{ route('affiliate.update', $affiliate->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label>First Name<span class="text-red-500">*</span></label>
            <input type="text" name="first_name" value="{{ $affiliate->first_name }}" required>
        </div>

        <div>
            <label>Last Name<span class="text-red-500">*</span></label>
            <input type="text" name="last_name" value="{{ $affiliate->last_name }}" required>
        </div>

        <div>
            <label>Website</label>
            <input type="text" name="website" value="{{ $affiliate->website }}">
        </div>

        <div>
            <label>Company Name</label>
            <input type="text" name="company_name" value="{{ $affiliate->company_name }}">
        </div>

        <div>
            <label>Street Name<span class="text-red-500">*</span></label>
            <input type="text" name="street_name" value="{{ $affiliate->street_name }}" required>
        </div>

        <div>
            <label>City<span class="text-red-500">*</span></label>
            <input type="text" name="city" value="{{ $affiliate->city }}" required>
        </div>

        <div>
            <label>Country<span class="text-red-500">*</span></label>
            <input type="text" name="country" value="{{ $affiliate->country }}" required>
        </div>

        <div>
            <label>State<span class="text-red-500">*</span></label>
            <input type="text" name="state" value="{{ $affiliate->state }}" required>
        </div>

        <div>
            <label>Zip Code<span class="text-red-500">*</span></label>
            <input type="text" name="zipcode" value="{{ $affiliate->zipcode }}" required>
        </div>

        <div>
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="{{ $affiliate->phone_number }}">
        </div>

        <div>
            <label>VAT Number</label>
            <input type="text" name="vat_number" value="{{ $affiliate->vat_number }}">
        </div>

        <div>
            <label>Minimum Payout:</label>
            <input type="number" step="1" name="minimum_payout" value="{{ $affiliate->minimum_payout }}">
        </div>

        <div>
            <label>Payout Method</label>
            <select name="payout_method">
                <option value="paypal" {{ $affiliate->payout_method == 'paypal' ? 'selected' : '' }}>PayPal</option>
                {{-- Add other payout methods as necessary --}}
            </select>
        </div>

        <div>
            <label>PayPal Email</label>
            <input type="email" name="paypal_email" value="{{ $affiliate->paypal_email }}">
        </div>

        <button type="submit">Update</button>
    </form>

</x-app-layout>
