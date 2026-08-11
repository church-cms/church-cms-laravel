<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class HelpAddRequest extends FormRequest
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
        Validator::extend('alpha_spaces', function ($attribute, $value,$parameters,$validator) {

              // This will only accept alpha and spaces. 
              // If you want to accept hyphens use: /^[\pL\s-]+$/u.
              return preg_match('/^[\pL\s]+$/u', request('title')); 
            });
        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('title'));  
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('description'));  
        });

        return [
            //
            'title'             =>  'required|check_title|max:20',
            'description'       =>  'required|max:100|check_description',
            'contact_details'   =>  'required|numeric|digits:10',
        ];
    }

    public function messages()
    {
        return[
            'title.required'                => 'Title is required',
            'title.check_title'             => 'Enter Valid Title',
            'title.max:20'                  => 'Title should not be more than 20 words',
            'description.required'          => 'Description is required',
            'description.check_description' => 'Enter Valid Description',
            'description.max:100'           => 'The description may not be greater than 100 characters.', 
            'contact_details.required'      => 'Contact Number is required', 
            'contact_details.numeric'       => 'Contact Number should be numeric', 
            'contact_details.digits:10'     => 'Contact Number should be 10 digits', 
        ];
    }
}
