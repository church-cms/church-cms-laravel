<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class GalleryUpdateRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('name'));  
        });

        $rules = [
            'name'          => 'required|max:30|check_name',
        ];
        if(request('path') != null)
        {
            $rules['path'] = 'nullable|mimes:jpg,jpeg,png';
        }

        return $rules;
    }

    public function messages()
    {
        return[
           
            'name.required'                 => 'Gallery Name is required',
            'name.check_name'               => 'Enter Valid Gallery Name',
            'name.max:30'                   => 'Gallery Name Cannot Be More Than 30 Characters',

            'path.mimes'                    => 'Choose an image file',
        ];
    }
}