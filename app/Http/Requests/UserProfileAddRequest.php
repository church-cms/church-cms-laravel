<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Userprofile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserProfileAddRequest extends FormRequest
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
        Validator::extend('check_marriage_start_date',function($attribute,$value,$parameters,$validator)
        {
            if(request('gender')=="female")
            {
                $user = User::where('name',request('ref_name'))->first();
                if(date('Y-m-d',strtotime($user->userprofile->marriage_start_date))==date('Y-m-d',strtotime(request('marriage_start_date'))))
                {
                    return true;
                }
                return false;
            }
            return true;
        });

        Validator::extend('check_marriage_end_date',function($attribute,$value,$parameters,$validator)
        {
            if(request('gender')=="female")
            {
                $user = User::where('name',request('ref_name'))->first();
                if(date('Y-m-d',strtotime($user->userprofile->marriage_end_date))==date('Y-m-d',strtotime(request('marriage_end_date'))))
                {
                    return true;
                }
                return false;
            }
            return true;
        });

        Validator::extend('checkunique_name',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('name','LIKE','%'.request('name').'%')->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

       Validator::extend('checkunique_email',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('church_id',Auth::user()->church_id)->where('email',request('email'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

        Validator::extend('checkunique_mobile',function($attribute,$value,$parameters,$validator)
        {
             
             $user=User::where('church_id',Auth::user()->church_id)->where('mobile_no','=',request('mobile_no'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

        Validator::extend('check_date_of_birth',function($attribute,$value,$parameters,$validator)
        { 
            if((request('date_of_birth')<=date('Y-m-d')) && (request('date_of_birth')>="1920-01-01"))
            {
                return true;
            }
                
            return false;
        });

        Validator::extend('checkname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^([A-Za-z])+([0-9])|([A-Za-z])+$/', request('name')) ;
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

        Validator::extend('check_unique_aadhar_number',function($attribute,$value,$parameters,$validator)
        {
            $userprofile = Userprofile::where('aadhar_number',request('aadhar_number'))->exists();
            if($userprofile)
            {
                return false;
            }
            return true;
        });

        $rules=[
            //
                //'name'              =>'required|checkname|max:10|checkunique_name',
                'firstname'         =>'required|check_firstname|max:15',
                'lastname'          =>'nullable|check_lastname|max:15',
                'birth_firstname'   =>'nullable|check_birth_firstname|max:15',
                'birth_lastname'    =>'nullable|check_birth_lastname|max:15',
                'gender'            =>'required',
                'date_of_birth'     =>'required|date|check_date_of_birth',
                //'was_baptized'      =>'required',
                'profession'        =>'required',
                'city_id'           =>'required',
                'state_id'          =>'required',
                'country_id'        =>'required',
                'pincode'           =>'required|numeric|digits:6',
                'mobile_no'         =>'required|numeric|digits:10|checkunique_mobile',
                'email'             =>'nullable|email|checkunique_email',
                //'membership_type'   =>'required',
                'family'            =>'nullable|checkfamily|max:15',
                'marriage_status'   =>'required',
                'aadhar_number'     =>'nullable|numeric|digits:12|check_unique_aadhar_number',
                'notes'             =>'nullable|string|checknotes',
                'avatar'            =>'required|mimes:jpg,jpeg,png,webp',
        ];

        if(request('ref_name')!="")
        {
            $rules['relation']='required';
        }

        /*if(request('was_baptized')=="yes")
        {
            $rules['baptism_date']='required';
        }*/

        if(request('membership_type')=="member")
        {
            $rules['membership_start_date']='required';
        }

        if(request('marriage_status')!= "single")
        {
            $rules['marriage_start_date']='nullable|check_marriage_start_date';

            /*if((request('marriage_status')== "ended_by_death") || (request('marriage_status')== "ended_by_divorce") || (request('marriage_status')== "separated"))
            {
                $rules['marriage_end_date']='nullable|check_marriage_end_date';
            }*/
        }

        if( (request('profession')!= '') && (request('profession')!= 'home_maker') && (request('profession')!= 'self_employed') && (request('profession')!= 'student') )
        { 
            $rules['sub_occupation']='required|checkoccupation|max:15';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'name.required'                                 =>  'User Name is required',
            'name.checkname'                                =>  'Enter a Valid User Name',
            'name.checkunique_name'                         =>  'User Name already in use. Try different User Name',
            'name.max:15'                                   =>  'User Name should be atmost 15 digits',

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

            'city_id.required'                              =>  'City is required',

            'state_id.required'                             =>  'State is required',

            'country_id.required'                           =>  'Country is required',

            'pincode.required'                              =>  'Pincode is required',
            'pincode.numeric'                               =>  'Pincode should be numeric',
            'pincode.digits:6'                              =>  'Pincode should be 6 digits',

            'mobile_no.required'                            =>  'Mobile Number is required',
            'mobile_no.numeric'                             =>  'Mobile Number should be numeric',
            'mobile_no.digits:10'                           =>  'Mobile Number should be 10 digits',
            'mobile_no.checkunique_mobile'                  =>  'Mobile Number already in use. Enter different Mobile Number',

            'email.required'                                =>  'Email ID is required',
            'email.email'                                   =>  'Enter a valid Email ID ',
            'email.checkunique_email'                       =>  'Email ID already in use. Enter different Email ID',

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

            'relation.required'                             =>  'Choose a relation', 

            'aadhar_number.required'                        =>  'Aadhaar Number is required',
            'aadhar_number.numeric'                         =>  'Aadhaar Number should be Numeric',
            'aadhar_number.digits:12'                       =>  'Aadhaar Number should be of 12 digits',
            'aadhar_number.check_unique_aadhar_number'      =>  'Aadhaar Number Already In Use. Enter Different Aadhaar Number',

            'notes.string'                                  =>  'Enter Valid Notes',
            'notes.checknotes'                              =>  'Enter Valid Notes',

            'avatar.required'=>'Avatar is required',
            'avatar.mimes'=>'Choose jpg,jpeg,png,webp file',
        ];
    }
}