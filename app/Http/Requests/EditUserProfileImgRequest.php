<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class EditUserProfileImgRequest extends FormRequest
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
        

        $rules = [
            
        ];


        if (request('avatar') != '') {
            $rules['avatar'] = 'required|mimes:jpg,jpeg,png,bmp,webp';
        }
        return $rules;
    }

    public function messages()
    {
        return
            [
                
                'avatar.required' => 'Avatar is required',
                'avatar.mimes' => 'Choose an image file',
            ];
    }
}
