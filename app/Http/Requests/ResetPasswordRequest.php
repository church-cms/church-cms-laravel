<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ResetPasswordRequest extends FormRequest
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
        Validator::extend('check_mobile_no',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('mobile_no','=',request('mobile_no'))->exists();
            if($user)
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_active',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('mobile_no','=',request('mobile_no'))->first();
            if($user->userprofile->status == 'active')
            {
                return true;
            }
            return false;
        });

        return [
            'mobile_no' =>  'required|numeric|digits:10|check_mobile_no|check_active',
        ];
    }

    public function messages()
    {
        $user = User::where('mobile_no','=',request('mobile_no'))->first();
        if($user->userprofile->status == 'inactive')
        {
            $status = 'User Account Suspended. Please Contact Admin';
        }
        elseif ($user->userprofile->status == 'exit') 
        {
            $status = 'You have exited this church';
        }

        return[
            'mobile_no.required'        =>  'Mobile Number Is Required',
            'mobile_no.numeric'         =>  'Mobile Number Should Be Numeric',
            'mobile_no.digits:10'       =>  'Mobile Number Should Be 10 Digits',
            'mobile_no.check_mobile_no' =>  'Mobile Number Not Registered In Church',
            'mobile_no.check_active'    =>  $status,
        ];
    }
}