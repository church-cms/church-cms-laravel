<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingGeneralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'church_full_name'  => 'required|string|max:255',
            'church_short_name' => 'nullable|string|max:100',
            'church_logo'       => 'nullable|image|mimes:jpeg,png,gif,svg|max:2048',
            'favicon'           => 'nullable|image|mimes:png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'church_full_name.required' => 'Church Full Name is required.',
            'favicon.mimes'             => 'Favicon must be a PNG file.',
        ];
    }
}