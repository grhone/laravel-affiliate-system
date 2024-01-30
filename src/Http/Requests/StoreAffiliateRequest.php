<?php

namespace Grhone\LaravelAffiliateSystem\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffiliateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Update this method to determine if the user is authorized to make this request.
        // You might check if the user is authenticated, has certain roles, etc.
        // Return true if authorization is not required or implement your logic.
        
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
            'user_id' => 'required|exists:users,id|unique:affiliates,user_id',
            // Add other fields and rules as per your affiliate model
            // For example:
            // 'referral_code' => 'required|unique:affiliates,referral_code',
            // 'approved' => 'sometimes|boolean',
            // Additional fields and rules...

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
            // Custom validation messages, if needed
            // For example:
            // 'user_id.required' => 'A user ID is required for affiliate registration.',
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
            // Custom attribute names, if needed
            // For example:
            // 'user_id' => 'user identifier',
            // Additional custom attribute names...
        ];
    }
}
