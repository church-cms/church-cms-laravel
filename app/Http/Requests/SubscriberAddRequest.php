<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscribers;

class SubscriberAddRequest extends FormRequest
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
        Validator::extend('check_firstname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('firstname')) ;
        });

        Validator::extend('check_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname')) ;
        });

        Validator::extend('check_aff',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('aff')) ;
        });

        Validator::extend('check_source',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('source')) ;
        });

        Validator::extend('check_unique_email',function($attribute,$value,$parameters,$validator)
        {
            $user = Subscribers::where('email','LIKE','%'.request('email').'%')->exists();
            if($user)
            {
                return false;
            }
            return true;
        });
       
        return [
            
            'firstname'    => 'required|check_firstname',
            'lastname'     => 'required|check_lastname',
            'email'        => 'required|email|check_unique_email',
            'aff'          => 'required|check_aff',
            'source'       => 'required|check_source',
        ];
    }

    public function messages()
    {
        return[
            'firstname.required'        => 'First Name Is Required',
            'firstname.check_firstname' => 'Enter Valid First Name',

            'lastname.required'         => 'Last Name Is Required',
            'lastname.check_lastname'   => 'Enter Valid Last Name',

            'email.required'            => 'Email Is Required',
            'email.email'               => 'Enter Valid Email',
            'email.check_unique_email'  => 'Email already Exists. Try Another email',

            'aff.required'              => 'Aff Is Required',
            'aff.check_aff'             => 'Enter Valid Aff',

            'source.required'           => 'Source Is Required',
            'source.check_source'       => 'Enter Valid Source',
        ];
    }
}