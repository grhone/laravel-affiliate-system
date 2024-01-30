{{-- Create View --}}

<x-app-layout>

    <h1>Register for Affiliate Account</h1>

    <form method="POST" action="{{ route('affiliate.register.store') }}">
        @csrf

        <div>
            <label>First Name<span class="text-red-500">*</span></label>
            <input type="text" name="first_name" required>
        </div>

        <div>
            <label>Last Name<span class="text-red-500">*</span></label>
            <input type="text" name="last_name" required>
        </div>

        <div>
            <label>Website</label>
            <input type="text" name="website">
        </div>

        <div>
            <label>Company Name</label>
            <input type="text" name="company_name">
        </div>

        <div>
            <label>Street Name<span class="text-red-500">*</span></label>
            <input type="text" name="street_name" required>
        </div>

        <div>
            <label>City<span class="text-red-500">*</span></label>
            <input type="text" name="city" required>
        </div>

        <div>
            <label>Country<span class="text-red-500">*</span></label>
            <input type="text" name="country" required>
        </div>

        <div>
            <label>State<span class="text-red-500">*</span></label>
            <input type="text" name="state" required>
        </div>

        <div>
            <label>Zip Code<span class="text-red-500">*</span></label>
            <input type="text" name="zipcode" required>
        </div>

        <div>
            <label>Phone Number</label>
            <input type="text" name="phone_number">
        </div>

        <div>
            <label>VAT Number</label>
            <input type="text" name="vat_number">
        </div>

        <div>
            <label>Payout Method</label>
            <select name="payout_method">
                <option value="paypal">PayPal</option>
                {{-- Add other payout methods as needed --}}
            </select>
        </div>

        <div>
            <label>PayPal Email</label>
            <input type="email" name="paypal_email">
        </div>

        <button type="submit">Register</button>
    </form>

</x-app-layout>
