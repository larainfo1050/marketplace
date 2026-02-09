<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by policy in controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'city' => 'required|string|max:100',
            'suburb' => 'required|string|max:100',
            'pricing_type' => 'required|in:hourly,fixed',
            'price_amount' => 'required|numeric|min:0|max:999999.99',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your listing.',
            'description.min' => 'Description must be at least 50 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'price_amount.required' => 'Please specify your pricing.',
            'price_amount.numeric' => 'Price must be a valid number.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'price_amount' => 'price',
            'pricing_type' => 'pricing type',
        ];
    }
}