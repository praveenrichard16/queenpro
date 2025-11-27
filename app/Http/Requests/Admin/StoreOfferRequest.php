<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) ($this->user()?->is_admin ?? false);
    }

    public function rules(): array
    {
        $rules = [
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after:valid_from'],
            'status' => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
            'offer_type' => ['required', 'in:common,product,category,brand,user,billing_amount'],
            'user_segment' => ['nullable', 'in:all,first_time_buyers,repeat_customers,minimum_purchase'],
            'minimum_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'is_public' => ['sometimes', 'boolean'],
        ];

        // Conditional validation based on offer_type
        $offerType = $this->input('offer_type');

        switch ($offerType) {
            case 'product':
                $rules['product_ids'] = ['required', 'array', 'min:1'];
                $rules['product_ids.*'] = ['required', 'exists:products,id'];
                break;

            case 'category':
                $rules['category_ids'] = ['required', 'array', 'min:1'];
                $rules['category_ids.*'] = ['required', 'exists:categories,id'];
                break;

            case 'brand':
                $rules['brand_ids'] = ['required', 'array', 'min:1'];
                $rules['brand_ids.*'] = ['required', 'exists:brands,id'];
                break;

            case 'user':
                $rules['user_ids'] = ['required', 'array', 'min:1'];
                $rules['user_ids.*'] = ['required', 'exists:users,id'];
                break;

            case 'billing_amount':
                $rules['min_amount'] = ['required', 'numeric', 'min:0'];
                break;
        }

        // Validation for user_segment
        if ($this->input('user_segment') === 'minimum_purchase') {
            $rules['minimum_purchase_amount'] = ['required', 'numeric', 'min:0'];
        }

        // Validation for percentage type
        if ($this->input('type') === 'percentage') {
            $rules['value'] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'This coupon code already exists.',
            'product_ids.required' => 'Please select at least one product.',
            'category_ids.required' => 'Please select at least one category.',
            'brand_ids.required' => 'Please select at least one brand.',
            'user_ids.required' => 'Please select at least one user.',
            'min_amount.required' => 'Minimum amount is required for billing amount offers.',
            'minimum_purchase_amount.required' => 'Minimum purchase amount is required for this user segment.',
            'value.max' => 'Percentage value cannot exceed 100%.',
        ];
    }
}

