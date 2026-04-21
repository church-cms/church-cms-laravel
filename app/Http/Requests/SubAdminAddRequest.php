<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class SubAdminAddRequest extends FormRequest
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
            return preg_match('/^[A-Za-z\s]+$/', request('firstname'));
        });

        Validator::extend('check_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname'));
        });

        Validator::extend('check_birth_firstname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_firstname'));
        });

        Validator::extend('check_birth_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_lastname'));
        });

        Validator::extend('check_date_of_birth',function($attribute,$value,$parameters,$validator)
        { 
            if((request('date_of_birth')<=date('Y-m-d')) && (request('date_of_birth')>="1920-01-01"))
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('check_unique_email',function($attribute,$value,$parameters,$validator)
        {
            $user=User::where('email','LIKE','%'.request('email').'%')->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_unique_mobile',function($attribute,$value,$parameters,$validator)
        {
            $user=User::where('mobile_no','=',request('mobile_no'))->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_occupation',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_occupation'));
        });

        Validator::extend('check_notes',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('notes'));
        });

        $rules= [
            'firstname'         =>  'required|check_firstname|max:15',
            'lastname'          =>  'nullable|check_lastname|max:15',
            'birth_firstname'   =>  'nullable|check_birth_firstname|max:15',
            'birth_lastname'    =>  'nullable|check_birth_lastname|max:15',
            'aadhar_number'     =>  'nullable|numeric|digits:12',
            'date_of_birth'     =>  'required|date|check_date_of_birth',
            'mobile_no'         =>  'required|numeric|digits:10|check_unique_mobile',
            'email'             =>  'nullable|email|check_unique_email',
            'gender'            =>  'required',
            'profession'        =>  'required',
            'country_id'        =>  'required',
            'state_id'          =>  'required',
            'city_id'           =>  'required',
            'pincode'           =>  'required|numeric|digits:6',
            'avatar'            =>  'required|mimes:jpg,jpeg,png,webp',
            'notes'             =>  'nullable|check_notes',
        ];


        if( (request('profession')!= '') && (request('profession')!= 'home_maker') && (request('profession')!= 'self_employed') && (request('profession')!= 'student') )
        { 
            $rules['sub_occupation']='required|check_occupation|max:15';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'firstname.required'                    =>'First Name is required',
            'firstname.check_firstname'             =>'Enter a Valid First Name',
            'firstname.max:15'                      =>'First Name should be atmost 15 digits',

            'lastname.check_lastname'               =>'Enter a Valid Last Name',
            'lastname.max:15'                       =>'Last Name should be atmost 15 digits',

            'birth_firstname.check_birth_firstname' =>'Enter a Valid Birth First Name',
            'birth_firstname.max:15'                =>'Birth First Name should be atmost 15 digits',

            'birth_lastname.check_birth_lastname'   =>'Enter a Valid Birth Last Name',
            'birth_lastname.max:15'                 =>'Birth  Last Name should be atmost 15 digits',

            'aadhar_number.required'                =>'Aadhaar Number is required',
            'aadhar_number.numeric'                 =>'Aadhaar Number should be Numeric',
            'aadhar_number.digits:12'               =>'Aadhaar Number should be of 12 digits',

            'date_of_birth.required'                =>'Date Of Birth is required',
            'date_of_birth.check_date_of_birth'     =>'Enter valid Date Of Birth',

            'mobile_no.required'                    =>'Mobile Number is required',
            'mobile_no.numeric'                     =>'Mobile Number should be numeric',
            'mobile_no.digits:10'                   =>'Mobile Number should be 10 digits',
            'mobile_no.check_unique_mobile'         =>'Mobile Number already in use. Enter different Mobile Number',

            'email.required'                        =>'Email ID is required',
            'email.email'                           =>'Enter a valid Email ID ',
            'email.check_unique_email'              =>'Email ID already in use. Enter different Email ID',

            'gender.required'                       =>'Gender is required',

            'profession.required'                   =>'Occupation is required',

            'sub_occupation.required'               =>'Sub Category is required',
            'sub_occupation.check_occupation'       =>'Enter a Valid Sub Category',
            'sub_occupation.max:15'                 =>'Sub Category should be atmost 15 digits',

            'country_id.required'                   =>'Country is required',

            'state_id.required'                     =>'State is required',

            'city_id.required'                      =>'City is required',

            'pincode.required'                      =>'Pincode is required',
            'pincode.numeric'                       =>'Pincode should be numeric',
            'pincode.digits:6'                      =>'Pincode should be 6 digits',

            'avatar.required'                       =>'Avatar is required',
            'avatar.mimes'                          =>'Choose jpg,jpeg,png,webp file',
            
            'notes.string'                          =>'Enter Valid Notes',
            'notes.check_notes'                     =>'Enter Valid Notes',
        ];
    }
}