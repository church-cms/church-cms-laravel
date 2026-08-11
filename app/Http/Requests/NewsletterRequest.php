<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class NewsletterRequest extends FormRequest
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
        Validator::extend('check_subject', function ($attribute, $value, $parameters, $validator) 
        {   
           return preg_match('/^\p{L}[\p{L} A-Za-z0-9_~\-!,@#\$%\^&*.:(\)((?:\'|").*(?:\'|"))\s]+$/u',request('subject'));       
        });

        return[
            'to'        =>  'required',
            'subject'   =>  'required|check_subject',
            'message'   =>  'required',
        ];
    }

    public function messages()
    {
        return[
           'to.required'            =>  'To Is Required',

           'subject.required'       =>  'Subject Is Required',
           'subject.check_subject'  =>  'Enter Valid Subject',

           'message.required'       =>  'Message Is Required',
        ];
    }
}