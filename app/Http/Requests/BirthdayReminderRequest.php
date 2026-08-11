<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class BirthdayReminderRequest extends FormRequest
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
        Validator::extend('check_message', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('message'));  
        });

        return [
            //
            'to'         => 'required',
            'message'    => 'required|max:150|check_message',
        ];
    }

    public function messages()
    {
        return[
            'to.required'           => 'To is required',
            'message.required'      => 'Message is required',
            'message.check_message' => 'Enter Valid Message',
            'message.max:150'       => 'Message should be atmost 150 letters',     
        ];
    }
}
