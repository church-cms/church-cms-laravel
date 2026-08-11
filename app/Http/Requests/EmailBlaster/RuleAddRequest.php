<?php

namespace App\Http\Requests\EmailBlaster;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RuleAddRequest extends FormRequest
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
        Validator::extend('check_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('name')) ;
        });

        Validator::extend('check_day_after',function($attribute,$value,$parameters,$validator)
        {
            if( ( request('day_after') >= 0 ) && ( request('day_after') <= 10 ) )
            {
                return true;
            }
            return false;
        });
        
        return [
            //
            'campaign_id'               =>  'required',
            'name'                      =>  'required|check_name',
            'email_open_campaign_id'    =>  'required',
            'no_email_open_campaign_id' =>  'required',
            'day_after'                 =>  'nullable|numeric|check_day_after',
        ];
    }

    public function messages()
    {
        return [
            //
            'campaign_id.required'                  =>  'Campaign Is Required',

            'name.required'                         =>  'Title Is Required',
            'name.check_name'                       =>  'Enter Valid Title',

            'email_open_campaign_id.required'       =>  'Campaign (Email Open from get response) Is Required',

            'no_email_open_campaign_id.required'    =>  'Campaign (No Mail opened from get response) Is Required',

            'day_after.numeric'                     =>  'Day After Shoule Be Numeric',
            'day_after.check_day_after'             =>  'Day After Shoule Be Greater Than 0 And Lesser Than 10',
        ];
    }
}