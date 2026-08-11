<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Authentication;
use App\Models\User;
use Hash;

class ResetChangePasswordRequest extends FormRequest
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
            $user = User::where('mobile_no',request('mobile_no'))->first();

            $authentication = Authentication::where([
                ['user_id',$user->id],
                ['status',1]
            ])->orderBy('id','DESC')->get();

            if($authentication[0]['token'] == request('oldpassword'))
            {
                return TRUE;
            } 
            return FALSE;
        }); 
         
        return [
            'oldpassword'       => 'required|checkpassword', 
            'newpassword'       => 'required|min:8', 
            'confirmpassword'   => 'required|same:newpassword'              
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
            'oldpassword.required'      =>  __('userprofile.oldpassword'), 
            'oldpassword.checkpassword' =>  __('userprofile.oldpassword_err'), 
            'newpassword.required'      =>  __('userprofile.newpassword'),
            'newpassword.min:8'         =>  __('userprofile.newpassword_min'),
            'confirmpassword.required'  =>  __('userprofile.confirmpassword'),
            'confirmpassword.same'      =>  __('userprofile.same_confirmpassword'),              
        ];
    }
}