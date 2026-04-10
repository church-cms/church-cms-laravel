<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Userprofile;
use App\Models\User;

class PreacherAddRequest extends FormRequest
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
        
        Validator::extend('checkunique_name',function($attribute,$value,$parameters,$validator)
        {
            $user=User::where('name',request('name'))->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('checkunique_email',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('email',request('email'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

         Validator::extend('checkunique_facebook_id',function($attribute,$value,$parameters,$validator)
        {
             $user=Userprofile::where('facebook_id',request('facebook_id'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

        Validator::extend('checkunique_mobile',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('mobile_no','=',request('mobile_no'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

  

        $rules=[
            //
                //'name'              =>'required|alpha|max:15|checkunique_name',
                'mobile_no'         =>'required|numeric|digits:10|checkunique_mobile',
                'email'             =>'required|email|checkunique_email',
                'firstname'         =>'required|alpha|max:15',
                'description'       =>'required|max:100',
                'facebook_id'       =>'required|checkunique_facebook_id',
                'avatar'            =>'required|mimes:jpg,jpeg,png,bmp,webp',
        ];


        
        return $rules;
    }

    public function messages()
    {
        return[
            'name.required'=>'User Name is required',
            'name.alpha'=>'Enter a Valid User Name',
            'name.checkunique_name'=>'User Name already in use. Try different User Name',
            'name.max:15'=>'User Name should be atmost 15 digits',
            'email.required'=>'Email ID is required',
            'email.email'=>'Enter a valid Email ID ',
            'email.checkunique_email'=>'Email ID already in use. Enter different Email ID',
            'mobile_no.required'=>'Mobile Number is required',
            'mobile_no.numeric'=>'Mobile Number should be numeric',
            'mobile_no.digits:10'=>'Mobile Number should be 10 digits',
            'mobile_no.checkunique_mobile'=>'Mobile Number already in use. Enter different Mobile Number',
            'firstname.required'=>'First Name is required',
            'firstname.alpha'=>'Enter a Valid First Name',
            'firstname.max:15'=>'First Name should be atmost 15 digits',
            'description.required'=>'Description is required',
            'description.max:100'=>'Description should not exceed 100 letters',
            'facebook_id.required'=>'Facebook ID is required',
            'facebook_id.checkunique_facebook_id'=>'Facebook ID already in use. Enter different Fb ID',
            'avatar.required'=>'Avatar is required',
            'avatar.mimes'=>'Choose an image file',
        ];
    }
}
