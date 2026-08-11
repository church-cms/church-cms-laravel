<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Church;
use App\Models\User;
use Hash;

class LoginRequest extends FormRequest
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
      $this->user = User::where('mobile_no', request('email'))->with('userprofile')->first();

      Validator::extend('checkchurch', function ($attribute, $value, $parameters, $validator) 
      {
            $church = Church::IsActive($this->user->church_id)->exists();

                if($church == FALSE)
                { 
                    return FALSE;
                }
                else
                {
                    return TRUE;
                }      
        });

     Validator::extend('checkstatus', function ($attribute, $value, $parameters, $validator) {
            
          if (count($this->user) > 0) 
          {
            if ($this->user->userprofile->status == 'active') 
            {
              return true;
            }
            else
            {
              return false;
            }
          }
      });

     Validator::extend('checkexit', function ($attribute, $value, $parameters, $validator) {
            
          if (count($this->user) > 0) 
          {
            if ($this->user->userprofile->status == 'exit') 
            {
              return false;
            }
            else
            {
              return true;
            }
          }
      });

     Validator::extend('checkuser', function ($attribute, $value, $parameters, $validator) {
    
        if(is_null($this->user))
          {       
            return false;
          }
          return true; 
      });

      Validator::extend('checkpassword', function ($attribute, $value, $parameters, $validator) {
        if (count($this->user) > 0) 
        {
          $password = $this->user->password;
          if (Hash::check(request('password'), $password)) 
          {
            return true;
          }
          return false;
        }
      });

      Validator::extend('checkusergroupid', function ($attribute, $value, $parameters, $validator) {
        if (count($this->user) > 0) 
        {
          if ($this->user->usergroup_id == '5') 
          {
            return true;
          }
          return false;
        }
      });

      $rules=[
        'email' => 'bail|checkuser|checkchurch|checkexit|checkstatus|checkpassword|checkusergroupid|required',
        'password' => 'required',
      ];

      return $rules;
   }

    public function messages()
   {
      $messages=[
        'email.checkchurch'       =>  'Contact Church Admin for more Details',
        'email.checkstatus'       =>  'User Account Suspended. Please Contact Admin',
        'email.checkexit'         =>  'You have exited this church',
        'email.checkpassword'     =>  'Incorrect Password',
        'email.checkuser'         =>  'Invalid User',
        'email.checkusergroupid'  =>  'Invalid Group Id'
      ];
      return $messages;
   }
}