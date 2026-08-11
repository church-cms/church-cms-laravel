<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EmailRequest extends FormRequest
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
        Validator::extend('check_subject',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('subject'));
        });

        Validator::extend('check_from_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[\sA-Za-z_-]+$/', request('from_name'));
        });

        return [
            //
            'subject'           =>  'required|check_subject',
            'from_email'        =>  'required|email',
            'from_name'         =>  'required|check_from_name',
            'reply_to_email'    =>  'required|email',
        ];
    }

    public function messages()
    {
        return [
            //
            'subject.required'          =>  'Subject Is Required',
            'subject.check_subject'     =>  'Enter Valid Subject',

            'from_email.required'       =>  'From Email Is Required',
            'from_email.email'          =>  'Enter Valid From Email',

            'from_name.required'        =>  'From Name Is Required',
            'from_name.check_from_name' =>  'Enter Valid From Name',

            'reply_to_email.required'   =>  'Reply To Email Is Required',
            'reply_to_email.email'      =>  'Enter Valid Reply To Email',
        ];
    }
}