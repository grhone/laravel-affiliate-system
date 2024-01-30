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
        // Implement authorization logic.
        // For example, you might check if the user is authenticated or has a specific role.
        // Return true if authorization is not required for this request.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Define validation rules for updating an affiliate
            // For example:
            'referral_code' => 'required|string|unique:affiliates,referral_code,' . $this->affiliate->id,
            'approved' => 'sometimes|boolean',
            // Add other validation rules as needed

            'commission_rate' => 'nullable|numeric|between:0,100',

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
