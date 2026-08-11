<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePrayerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expiry_days' => 'required|integer|in:1,3,7,14,30,60,90',
            'text'        => 'nullable|string|min:10|max:500',
        ];
    }

    public function messages()
    {
        return [
            'expiry_days.required' => 'Please select an expiry duration.',
            'expiry_days.in'       => 'Invalid expiry duration selected.',
        ];
    }
}
