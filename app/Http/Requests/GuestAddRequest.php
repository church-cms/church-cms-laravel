<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Userprofile;
use App\Models\User;

class GuestAddRequest extends FormRequest
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

        Validator::extend('check_unique_aadhar_number',function($attribute,$value,$parameters,$validator)
        {
            $userprofile = Userprofile::where('aadhar_number',request('aadhar_number'))->exists();
            if($userprofile)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_date_of_birth',function($attribute,$value,$parameters,$validator)
        { 
            $date_of_birth = date('Y-m-d',strtotime(request('date_of_birth')));
            if( ( $date_of_birth <= date('Y-m-d') ) && ( $date_of_birth >= "1920-01-01" ) )
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('check_notes',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('notes')) ;
        });

        Validator::extend('check_occupation',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_occupation')) ;
        });
        $rules=[
            //
                'firstname'         =>  'required|check_firstname|max:15',
                'lastname'          =>  'nullable|check_lastname|max:15',
                'gender'            =>  'required',
                'date_of_birth'     =>  'required|date|check_date_of_birth',
                //'was_baptized'      =>    'required',
                'profession'        =>  'nullable',
                'city_id'           =>  'required',
                'state_id'          =>  'required',
                'country_id'        =>  'required',
                'pincode'           =>  'nullable|numeric|digits:6',
                'mobile_no'         =>  'required|numeric|digits:10|check_unique_mobile',
                'email'             =>  'nullable|email|check_unique_email',
                'aadhar_number'     =>  'nullable|numeric|digits:12|check_unique_aadhar_number',
                'notes'             =>  'nullable|string|check_notes',
                'sub_occupation'    =>  'nullable|check_occupation|max:15',
        ];

        /*if(request('was_baptized')=="yes")
        {
            $rules['baptism_date']='required';
        }*/

        return $rules;
    }

    public function messages()
    {
        return[
            'firstname.required'                        =>  'First Name Is Required',
            'firstname.check_firstname'                 =>  'Enter A Valid First Name',
            'firstname.max:15'                          =>  'First Name Should Be Atmost 15 Digits',
            
            'lastname.check_lastname'                   =>  'Enter A Valid Last Name',
            'lastname.max:15'                           =>  'Last Name Should Be Atmost 15 Digits',

            'gender.required'                           =>  'Gender Is Required',

            'date_of_birth.required'                    =>  'Date Of Birth Is Required',
            'date_of_birth.check_date_of_birth'         =>  'Enter Valid Date Of Birth',

            'was_baptized.required'                     =>  'Baptism Is Required',
            'baptism_date.required'                     =>  'Baptism Date Is Required',

            'mobile_no.required'                        =>  'Mobile Number Is Required',
            'mobile_no.numeric'                         =>  'Mobile Number Should Be Numeric',
            'mobile_no.digits:10'                       =>  'Mobile Number Should Be 10 Digits',
            'mobile_no.check_unique_mobile'             =>  'Mobile Number Already In Use. Enter Different Mobile Number',

            'email.required'                            =>  'Email ID Is Required',
            'email.email'                               =>  'Enter A valid Email ID ',
            'email.check_unique_email'                  =>  'Email ID Already In Use. Enter Different Email ID',

            'profession.required'                       =>  'Occupation Is Required',

            'sub_occupation.required'                   =>  'Sub Category Is Required',
            'sub_occupation.check_occupation'           =>  'Enter A Valid Sub Category',
            'sub_occupation.max:15'                     =>  'Sub Category Should Be Atmost 15 Digits',

            'city_id.required'                          =>  'City Is Required',

            'state_id.required'                         =>  'State Is Required',

            'country_id.required'                       =>  'Country Is Required',

            'pincode.required'                          =>  'Pincode Is Required',
            'pincode.numeric'                           =>  'Pincode Should Be Numeric',
            'pincode.digits:6'                          =>  'Pincode Should Be 6 Digits',

            'mobile_no.required'                        =>  'Mobile Number Is Required',
            'mobile_no.numeric'                         =>  'Mobile Number Should Be Numeric',
            'mobile_no.digits:10'                       =>  'Mobile Number Should Be 10 Digits',
            'mobile_no.checkunique_mobile'              =>  'Mobile Number Already In Use. Enter Different Mobile Number',

            'email.required'                            =>  'Email ID Is Required',
            'email.email'                               =>  'Enter A Valid Email ID ',
            'email.checkunique_email'                   =>  'Email ID Already In Use. Enter Different Email ID',

            'aadhar_number.required'                    =>  'Aadhaar Number Is Required',
            'aadhar_number.numeric'                     =>  'Aadhaar Number Should Be Numeric',
            'aadhar_number.digits:12'                   =>  'Aadhaar Number Should Be Of 12 Digits',
            'aadhar_number.check_unique_aadhar_number'  =>  'Aadhaar Number Already In Use. Enter Different Aadhaar Number',

            'notes.string'                              =>  'Enter Valid Notes',
            'notes.check_notes'                         =>  'Enter Valid Notes',
        ];
    }
}