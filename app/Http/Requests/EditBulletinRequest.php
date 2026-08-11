<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBulletinRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string', 'in:week,month'],
            'year'        => ['required', 'digits:4', 'integer'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'path'        => ['nullable', 'mimes:pdf', 'max:8192'],
        ];

        if ($this->input('type') === 'week') {
            $rules['week'] = ['required', 'integer', 'between:1,53'];
        } else {
            $rules['month'] = ['required', 'integer', 'between:1,12'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'       => 'Bulletin name is required.',
            'name.max'            => 'Bulletin name must not exceed 255 characters.',
            'type.required'       => 'Please select a bulletin type.',
            'type.in'             => 'Type must be either week or month.',
            'year.required'       => 'Year is required.',
            'year.digits'         => 'Year must be a 4-digit number.',
            'week.required'       => 'Week number is required.',
            'week.between'        => 'Week must be between 1 and 53.',
            'month.required'      => 'Month is required.',
            'month.between'       => 'Month must be between 1 and 12.',
            'cover_image.image'   => 'Cover image must be an image file.',
            'cover_image.mimes'   => 'Cover image must be jpg, jpeg, png, or webp.',
            'cover_image.max'     => 'Cover image must not exceed 2MB.',
            'path.mimes'          => 'Bulletin file must be a PDF.',
            'path.max'            => 'Bulletin file must not exceed 8MB.',
        ];
    }
}
