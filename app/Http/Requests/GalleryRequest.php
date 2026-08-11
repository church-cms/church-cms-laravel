<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class GalleryRequest extends FormRequest
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

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('description'));  
        });

        return [
            
            'name'          => 'required|max:30|check_name',
           // 'path'          => 'required|mimes:jpg,jpeg,png,webp',
            //'description'   => 'required|max:100|check_description',
        ];
    }

    public function messages()
    {
        return[
           
            'name.required'                 => 'Gallery Name is required',
            'name.check_name'               => 'Enter Valid Gallery Name',
            'name.max:30'                   => 'Gallery Name Cannot Be More Than 30 Characters',

            'path.required'                 => 'Cover Image is required',
            'path.mimes'                    => 'Choose an image file',

            'description.required'          => 'Description is required', 
            'description.check_description' => 'Enter Valid Description',  
            'description.max:100'           => 'The description may not be greater than 100 characters.', 
        ];
    }
}