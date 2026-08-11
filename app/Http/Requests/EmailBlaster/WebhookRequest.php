<?php

namespace App\Http\Requests\EmailBlaster;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class WebhookRequest extends FormRequest
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

        Validator::extend('check_handshake_key',function($attribute,$value,$parameters,$validator)
        {
            if( strlen( request('handshake_key') ) <= 255 )
            {
                return true;
            }
            return false;
        });
        
        return [
            //
            'mailinglist_id'    =>  'required',
            'name'              =>  'required|check_name',
            'handshake_key'     =>  'required|check_handshake_key',
            'webhook_url'       =>  'required|url',
        ];
    }

    public function messages()
    {
        return [
            //
            'mailinglist_id.required'           =>  'Mailing List Is Required',

            'name.required'                     =>  'Title Is Required',
            'name.check_name'                   =>  'Enter Valid Title',

            'handshake_key.required'            =>  'Handshake Key Is Required',
            'handshake_key.check_handshake_key' =>  'Handshake Key Cannot Be More Than 255 Characters',

            'webhook_url.required'              =>  'URL Is Required',
            'webhook_url.url'                   =>  'Enter Valid URL',
        ];
    }
}