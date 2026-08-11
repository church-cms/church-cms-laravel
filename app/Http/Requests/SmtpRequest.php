<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SmtpRequest extends FormRequest
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
        Validator::extend('check_host',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', request('host'));
        });

        Validator::extend('check_port',function($attribute,$value,$parameters,$validator)
        {
            if( ( request('port') >= 1 ) && ( request('port') <= 65536 ) )
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_username',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('username')) ;
        });

        return [
            //
            'host'         => 'required|check_host',
            'port'         => 'required|numeric|check_port',
            'username'     => 'required|check_username',
            'password'     => 'required|min:8',
            'encryption'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'host.required'             =>  'Host Is Required',
            'host.check_host'           =>  'Enter Valid Host',

            'port.required'             =>  'Port Is Required',
            'port.numeric'              =>  'Port Must Be Numeric',
            'port.check_port'           =>  'Port Should Be Between 1 And 65536',

            'username.required'         =>  'User Name Is Required',
            'username.check_username'   =>  'Enter Valid User Name',

            'password.required'         =>  'Password Is Required',
            'password.min'              =>  'Password Should Be Of Minimum 8 Characters',

            'encryption.required'       =>  'Encryption Is Required',
        ];
    }
}