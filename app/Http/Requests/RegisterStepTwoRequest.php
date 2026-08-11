<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterStepTwoRequest extends FormRequest
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
        Validator::extend('check_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^([A-Za-z])+([0-9])|([A-Za-z])+$/', request('name')) ;
        });

        Validator::extend('check_unique_name',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('name','LIKE','%'.request('name').'%')->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_unique_mobile',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('mobile_no','=',request('mobile_no'))->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_unique_email',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('email','LIKE','%'.request('email').'%')->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        $rules = [
            'name'                  =>  'required|check_name|max:15|check_unique_name',
            'mobile_no'             =>  'required|numeric|digits:10|check_unique_mobile',
            'email'                 =>  'required|email|check_unique_email',
            'password'              =>  'required|min:8|confirmed',   
            'termsandcondn'         =>  'required', 
        ];
        
        return $rules;
    }

    public function messages()
    {
        return[
            'name.required'                         => 'User Name is required',
            'name.check_name'                       => 'Enter a Valid User Name',
            'name.check_unique_name'                => 'User Name already exists. Try different User Name',
            'name.max:15'                           => 'User Name should be atmost 15 digits',

            'mobile_no.required'                    => 'Mobile Number is required',
            'mobile_no.numeric'                     => 'Mobile Number should be numeric',
            'mobile_no.digits:10'                   => 'Mobile Number should be 10 digits',
            'mobile_no.check_unique_mobile'         => 'Mobile Number already exists. Enter different Mobile Number',

            'email.required'                        => 'Email ID is required',
            'email.email'                           => 'Enter a valid Email ID ',
            'email.check_unique_email'              => 'Email ID already exists. Enter different Email ID',

            'password.required'                     => 'Password is required',
            'password.min:8'                        => 'Password should be atleast 8 digits',

            'termsandcondn.required'                => 'Terms and Condition is required',

            'g-recaptcha-response.required'         => 'Captcha Required',
        ];
    }
}