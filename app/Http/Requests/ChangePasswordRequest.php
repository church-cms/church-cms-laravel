<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class ChangePasswordRequest extends FormRequest
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
        Validator::extend('checkpassword', function ($attribute, $value, $parameters, $validator) 
        {
            $user = User::find(Auth::id());

            $hashedPassword = $user->password;

            if($user->password!='') 
            {
                if(Hash::check(request('oldpassword'),$hashedPassword)) 
                {
                    return TRUE;
                } 
                return FALSE;
            }
        }); 

        return [
            'oldpassword' => 'required|checkpassword', 
            'newpassword' => 'required|min:8', 
            'confirmpassword' => 'required|same:newpassword'              
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {      

        return [
            'oldpassword.required' =>__('userprofile.oldpassword'), 
            'oldpassword.checkpassword' => __('userprofile.oldpassword_err'), 
            'newpassword.required' =>__('userprofile.newpassword'),
            'newpassword.min:8' =>__('userprofile.newpassword_min'),
            'confirmpassword.required' =>__('userprofile.confirmpassword'),
            'confirmpassword.same' =>__('userprofile.same_confirmpassword'),              
        ];
    }
}