<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectPrayerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'required|string|min:3|max:300',
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => 'Please provide a reason for rejection.',
            'reason.max'      => 'Rejection reason must not exceed 300 characters.',
        ];
    }
}
