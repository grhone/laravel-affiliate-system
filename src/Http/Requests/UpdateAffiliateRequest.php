<?php

namespace Grhone\LaravelAffiliateSystem\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffiliateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only allow authenticated users to make this request
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Assuming 'user_id' will be fetched from Auth::user() and not from the request, so it's not included here
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'company_name' => 'nullable|string|max:255',
            'street_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'minimum_payout' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|between:0,100',
            'payout_method' => 'required|string|in:paypal', 
            'paypal_email' => 'nullable|email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Custom validation messages
            // For example:
            // 'referral_code.unique' => 'The referral code has already been taken.',
            // Additional custom messages...
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // Custom attribute names
            // For example:
            // 'referral_code' => 'affiliate referral code',
            // Additional custom attribute names...
        ];
    }
}
