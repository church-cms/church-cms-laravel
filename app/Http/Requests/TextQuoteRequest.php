<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TextQuoteRequest extends FormRequest
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
        Validator::extend('check_tamquotes', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('tamquotes'));  
        });
        
        Validator::extend('check_engquotes', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('engquotes'));  
        });

        $rules=[
            //
                'tamquotes'  =>'required|check_tamquotes',
                'engquotes'  =>'required|check_engquotes',
        ];
        return $rules;
    }

    public function messages()
    {   
      return
        [
          'tamquotes.required'          => 'Quote is Required',
          'tamquotes.check_tamquotes'   => 'Enter Valid Quote',
          'engquotes.required'          => 'Quote is Required',
          'engquotes.check_engquotes'   => 'Enter Valid Quote',
        ];
   } 
}