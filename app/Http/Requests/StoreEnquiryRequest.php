<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only customers can send enquiries
        return $this->user() && $this->user()->hasRole('customer');
    }

    public function rules(): array
    {
        return [
            'listing_id' => ['required', 'exists:listings,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'captcha_answer' => ['required', 'integer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verify CAPTCHA
            $num1 = session('captcha_num1');
            $num2 = session('captcha_num2');
            $correctAnswer = $num1 + $num2;

            if ($this->captcha_answer != $correctAnswer) {
                $validator->errors()->add('captcha_answer', 'Incorrect answer. Please try again.');
            }
        });
    }
}