<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitPrayerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:prayer_categories,id',
            'text'        => 'required|string|min:10|max:500',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Please select a prayer category.',
            'category_id.exists'   => 'The selected category is invalid.',
            'text.required'        => 'Please enter your prayer request.',
            'text.min'             => 'Your prayer request must be at least 10 characters.',
            'text.max'             => 'Your prayer request must not exceed 500 characters.',
        ];
    }
}
