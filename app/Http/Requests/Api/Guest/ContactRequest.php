<?php

namespace App\Http\Requests\Api\Guest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ContactRequest extends FormRequest
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
        Validator::extend('check_fullname', function ($attribute, $value, $parameters, $validator) 
        {
            return preg_match('/^[A-Za-z\s]+$/', request('fullname'));   
        });

        return [
            'church_id'     =>  'required',
            'fullname'      =>  'required|max:25|check_fullname',
            'email'         =>  'required|email',
            'mobile'        =>  'required|numeric|digits:10',
            'query_message' =>  'required|max:500',
        ];
    }

    public function messages()
    {
        return
        [ 
            'church_id.required'        =>  'Church Id Is Required',
            
            'fullname.required'         =>  'Full Name Is Required',
            'fullname.check_fullname'   =>  'Enter Valid Full Name',
            'fullname.max:25'           =>  'Full Name Cannot Be More Than 25 Characters',

            'email.required'            =>  'Email ID Is Required',
            'email.email'               =>  'Enter Valid Email ID',

            'mobile.required'           =>  'Mobile Number Is Required',
            'mobile.numeric'            =>  'Mobile Number Must Be Number',
            'mobile.digits'             =>  'Mobile Number Must Be 10 digits',

            'query_message.required'    =>  'Query Is Required',
            'query_message.max'         =>  'Query Cannot Be More Than 500 Characters',
        ];
    }
}