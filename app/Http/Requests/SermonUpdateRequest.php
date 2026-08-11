<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SermonUpdateRequest extends FormRequest
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
            //
            'title'       => 'required',
            'description' => 'required',
            //'cover_image' => 'nullable|mimes:jpeg,jpg,bmp,png',
        ];
    }

    public function messages()
    {

        return [
            'title.required' => 'Name is Required',
            'title.check_title'  => ' Enter Valid Title',
            'description.required' => 'Enter your description',
            'description.check_description' => 'Enter Valid Description',
            'cover_image.required' => 'Select your image',
            'cover_image.mimes' => 'file extension error',
        ];
    }
}
