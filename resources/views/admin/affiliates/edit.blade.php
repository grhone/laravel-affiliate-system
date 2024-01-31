{{-- Edit View --}}

<x-app-layout>

    <h1>Edit Affiliate</h1>

    <form method="POST" action="{{ route('admin.affiliate.update', $affiliate->id) }}">
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
            <label>Minimum Payout (Default if blank)</label>
            <input type="number" step="1" name="minimum_payout" value="{{ $affiliate->minimum_payout }}">
        </div>

        <div>
            <label>Commission Rate (%)  (Default if blank)</label>
            <input type="number" step="0.01" name="commission_rate" value="{{ $affiliate->commission_rate }}">
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
