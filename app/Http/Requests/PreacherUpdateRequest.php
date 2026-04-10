<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Userprofile;
use App\Models\User;

class PreacherUpdateRequest extends FormRequest
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

        Validator::extend('check_facebook',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', request('facebook_id'));
        });

        Validator::extend('check_unique_facebook_id',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('name',request('name'))->first();
            $userprofile = Userprofile::where([['user_id','!=',$user->id],['facebook_id','LIKE','%'.request('facebook_id').'%']])->exists();
            if($userprofile)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description')) ;
        });

        $rules=
        [
            //
            'firstname'         =>'required|check_firstname|max:15',
            'lastname'          =>'nullable|check_lastname|max:15',
            'description'       =>'required|max:300|check_description',
            'facebook_id'       =>'required|check_unique_facebook_id|check_facebook',    
        ];


        if(request('avatar')!= '')
        {
            $rules['avatar']='nullable|mimes:jpg,jpeg,png,bmp,webp';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'firstname.required'                    =>  'First Name Is Required',
            'firstname.check_firstname'             =>  'Enter A Valid First Name',
            'firstname.max'                         =>  'First Name Should Be Atmost 15 Digits',

            'lastname.check_lastname'               =>  'Enter A Valid Last Name',
            'lastname.max'                          =>  'Last Name Should Be Atmost 15 Digits',

            'description.required'                  =>  'Notes Is Required',
            'description.max'                       =>  'Notes Should Not Exceed 100 Characters',

            'facebook_id.required'                  =>  'Facebook ID Is Required',
            'facebook_id.check_facebook'            =>  'Enter Valid Facebook ID',
            'facebook_id.check_unique_facebook_id'  =>  'Facebook ID Already In Use. Enter Different Facebook ID',

            'avatar.required'                       =>  'Avatar Is Required',
            'avatar.mimes'                          =>  'Choose jpg or png or webp File',
        ];
    }
}