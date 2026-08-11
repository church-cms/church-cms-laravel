<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscribers;

class SubscriberRequest extends FormRequest
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
        Validator::extend('check_unique_email',function($attribute,$value,$parameters,$validator)
        {
            $user = Subscribers::where('email','LIKE','%'.request('email').'%')->exists();
            if($user)
            {
                return false;
            }
            return true;
        });
       
        return [
            
            'firstname'    => 'required',
            'lastname'     => 'required',
            'email'        => 'required|check_unique_email',
            'aff'          => 'required',
            'source'       => 'required',
        ];
    }

    public function messages()
    {
        return[           
            'firstname.required'   => 'Enter First Name',
            'lastname.required'    => 'Enter Last Name',
            'email.required'       => 'Enter Email',
            'email.unique'         => 'Email already Exists. Try Another email',
            'aff.required'         => 'Enter Aff',
            'source.required'      => 'Enter Source',
        ];
    }
}