<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class GroupUpdateRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            //validation for tamil letters
            return preg_match('/\pL\pM*|./u', request('name'));
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) {
            //validation for tamil letters
            return preg_match('/\pL\pM*|./u', request('description'));
        });

        $rules = [
            'category'      => 'required',
            'group_type'    => 'required',
            'name'          => 'required|max:30|check_name',
            'description'   => 'required|max:1000|check_description',
        ];

        if (request('cover_image') != '') {
            $rules['cover_image'] = 'nullable|mimes:jpg,jpeg,png';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'category.required'     => 'Category is required',
            'group_type.required'   => 'Group Type is required',
            'name.required'         => 'Group Name is required',
            'name.max:30'           => 'Group Name should be atmost 30 digits',
            'name.check_name'       => 'Enter Valid Group Name',
            'cover_image.mimes'     => 'Choose an image file',
            'description.required'  => 'Description is required',
            'description.check_description' => 'Enter Valid Description',
            'description.max:255'   => 'The description may not be greater than 255 characters.',
        ];
    }
}
