<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CampaignEmailRequest extends FormRequest
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
        Validator::extend('check_delay_in_days',function($attribute,$value,$parameters,$validator)
        {
            if( ( (int)request('delay_in_days') >= 0) && ( (int)request('delay_in_days') <= 10) )
            {
                return true;
            }
            return false;
        });

        return [
            //
            'email_id'      =>  'required',
            'delay_in_days' =>  'required|check_delay_in_days',
        ];
    }

    public function messages()
    {
        return[
            //
            'email_id.required'                 =>  'Email Is Required',

            'delay_in_days.required'            =>  'Delay In Days Is Required',
            'delay_in_days.check_delay_in_days' =>  'Delay In Days Can Be Between 0 To 10',
        ];
    }
}