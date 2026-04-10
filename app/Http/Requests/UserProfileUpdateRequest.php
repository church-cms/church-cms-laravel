<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Userprofile;
use App\Models\User;

class UserProfileUpdateRequest extends FormRequest
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
        //dd(request());
        Validator::extend('check_marriage_start_date',function($attribute,$value,$parameters,$validator)
        {
            if(request('gender')=="female")
            {
                $user = User::where('id',request('ref_id'))->first();
                if($user!='')
                {
                    if(date('Y-m-d',strtotime(optional($user->userprofile)->marriage_start_date))==date('Y-m-d',strtotime(request('marriage_start_date'))))
                    {
                        return true;
                    }
                    return false; 
                } 
               return true;
            }
            return true;  
        });

        Validator::extend('check_marriage_start_date_value',function($attribute,$value,$parameters,$validator)
        { 
            if((request('marriage_start_date')<=date('Y-m-d')) && (request('marriage_start_date')>="1940-01-01"))
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('check_marriage_end_date',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('id',request('ref_id'))->first();
            if(date('Y-m-d',strtotime(optional($user->userprofile)->marriage_end_date))==date('Y-m-d',strtotime(request('marriage_end_date'))))
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_marriage_end_date_value',function($attribute,$value,$parameters,$validator)
        { 
            if((request('marriage_end_date')<=date('Y-m-d')) && (request('marriage_end_date')>="1940-01-01"))
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('check_date_of_birth',function($attribute,$value,$parameters,$validator)
        { 
            if((request('date_of_birth')<=date('Y-m-d')) && (request('date_of_birth')>="1920-01-01"))
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('check_firstname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('firstname')) ;
        });

        Validator::extend('check_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname')) ;
        });

        Validator::extend('check_birth_firstname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_firstname')) ;
        });

        Validator::extend('check_birth_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_lastname')) ;
        });

        Validator::extend('checkaddress',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address')) ;
        });

        Validator::extend('checknotes',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('notes')) ;
        });

        Validator::extend('checkfamily',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('family')) ;
        });

        Validator::extend('checkoccupation',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_occupation')) ;
        });

        // Validator::extend('check_unique_aadhar_number',function($attribute,$value,$parameters,$validator)
        // {
        //     $user = User::where('name',request('name'))->first();
        //     $userprofile = Userprofile::where([['aadhar_number',request('aadhar_number')],['user_id','!=',$user->id]])->exists();
        //     if($userprofile)
        //     {
        //         return false;
        //     }
        //     return true;
        // });

    Validator::extend('check_unique_aadhar_number', function ($attribute, $value, $parameters, $validator) {

   $user = User::where('name',request('name'))->first();

   //dd($user);

    $exists = Userprofile::where('aadhar_number', request('aadhar_number'))
        ->where('user_id', '!=', $user->id)
        ->exists();

    return !$exists;
   });

        $rules=
        [
                'firstname'         =>  'required|check_firstname|max:15',
                'lastname'          =>  'nullable|check_lastname|max:15',
                'birth_firstname'   =>  'nullable|check_birth_firstname|max:15',
                'birth_lastname'    =>  'nullable|check_birth_lastname|max:15',
                'gender'            =>  'required',
                'date_of_birth'     =>  'required|date|check_date_of_birth',
                //'was_baptized'      =>  'required',
                'profession'        =>  'required',
                'city_id'           =>  'required',
                'state_id'          =>  'required',
                'country_id'        =>  'required',
                'pincode'           =>  'required|numeric|digits:6',
                'family'            =>  'nullable|checkfamily|max:15',
                'marriage_status'   =>  'required',
                'notes'             =>  'nullable|string|checknotes',
        ];

        /*if(request('was_baptized')=="yes")
        {
            $rules['baptism_date']='required';
        }*/

        if(request('aadhar_number') != 'null')
        {
            $rules['aadhar_number']='nullable|numeric|digits:12|check_unique_aadhar_number';
        }

        if(request('ref_id') != null)
        {
            $rules['relation'] = 'required';
        }

        if(request('marriage_status')!= "single")
        {
            $rules['marriage_start_date']='nullable|check_marriage_start_date|check_marriage_start_date_value';

            /*if((request('marriage_status')== "ended_by_death") || (request('marriage_status')== "ended_by_divorce") || (request('marriage_status')== "separated"))
            {
                $rules['marriage_end_date']='nullable|check_marriage_end_date|check_marriage_end_date_value';
            }*/

        }
        
        if( (request('profession')!= '') && (request('profession')!= 'home_maker') && (request('profession')!= 'self_employed') && (request('profession')!= 'student') )
        { 
            $rules['sub_occupation']='required|checkoccupation|max:15';
        }
        
        if(request('avatar')!= '')
        {
            $rules['avatar']='nullable|mimes:jpg,jpeg,png,webp';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'firstname.required'                            =>  'First Name is required',
            'firstname.check_firstname'                     =>  'Enter a Valid First Name',
            'firstname.max:15'                              =>  'First Name should be atmost 15 digits',

            'lastname.check_lastname'                       =>  'Enter a Valid Last Name',
            'lastname.max:15'                               =>  'Last Name should be atmost 15 digits',

            'birth_firstname.check_birth_firstname'         =>  'Enter a Valid Birth First Name',
            'birth_firstname.max:15'                        =>  'Birth First Name should be atmost 15 digits',

            'birth_lastname.check_birth_lastname'           =>  'Enter a Valid Birth Last Name',
            'birth_lastname.max:15'                         =>  'Birth  Last Name should be atmost 15 digits',
            'gender.required'                               =>  'Gender is required',

            'date_of_birth.required'                        =>  'Date Of Birth is required',
            'date_of_birth.check_date_of_birth'             =>  'Enter valid Date Of Birth',

            'was_baptized.required'                         =>  'Baptism is required',
            'baptism_date.required'                         =>  'Baptism Date is required',

            'profession.required'                           =>  'Occupation is required',

            'sub_occupation.required'                       =>  'Sub Category is required',
            'sub_occupation.checkoccupation'                =>  'Enter a Valid Sub Category',
            'sub_occupation.max:15'                         =>  'Sub Category should be atmost 15 digits',

            'address.required'                              =>  'Address is required',
            'address.checkaddress'                          =>  'Enter a Valid Address',

            'city_id.required'                              =>  'City is required',

            'state_id.required'                             =>  'State is required',

            'country_id.required'                           =>  'Country is required',

            'pincode.required'                              =>  'Pincode is required',
            'pincode.numeric'                               =>  'Pincode should be numeric',
            'pincode.digits:6'                              =>  'Pincode should be 6 digits',

            'membership_type.required'                      =>  'Membership Type is required',
            'membership_start_date.required'                =>  'Membership Start Date is required',

            'family.required'                               =>  'Family is required',
            'family.checkfamily'                            =>  'Enter a Valid Family Name',
            'family.max:15'                                 =>  'Family Name cannot exceed 15 letters',

            'marriage_status.required'                      =>  'Marriage Status is required',

            'marriage_start_date.required'                  =>  'Marriage Start Date is required',
            'marriage_start_date.check_marriage_start_date' =>  'Enter Valid Marriage Start Date',

            'marriage_end_date.required'                    =>  'Marriage End Date is required',
            'marriage_end_date.check_marriage_end_date'     =>  'Enter Valid Marriage End Date',

            'aadhar_number.required'                        =>  'Aadhaar Number is required',
            'aadhar_number.numeric'                         =>  'Aadhaar Number should be Numeric',
            'aadhar_number.digits:12'                       =>  'Aadhaar Number should be of 12 digits',
            'aadhar_number.check_unique_aadhar_number'      =>  'Aadhaar Number Already In Use. Enter Different Aadhaar Number',

            'notes.string'                                  =>  'Enter Valid Notes',
            'notes.checknotes'                              =>  'Enter Valid Notes',

            'avatar.required'                               =>  'Avatar is required',
            'avatar.mimes'                                  =>  'Choose jpg,jpeg,png,webp file',
        ];
    }
}