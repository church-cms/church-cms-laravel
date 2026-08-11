<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\MailinglistSubscriber;

class MaillistSubscriberRequest extends FormRequest
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
        Validator::extend('check_subscriber', function ($attribute, $value, $parameters, $validator) 
        {
            $maillistsubscriber = MailinglistSubscriber::where('subscribers_id',request('subscriber_id'))->where('mailing_list_id',request('mailinglist_id'))->whereNull('deleted_at')->exists();

            if($maillistsubscriber)
            { 
                return FALSE;
            }
            return TRUE;      
        });

        return [
            'subscriber_id' =>  'required|check_subscriber',
        ];
    }

    public function messages()
    {   
        return
        [
            'subscriber_id.required'            => 'Subscriber Is Required',
            'subscriber_id.check_subscriber'    => 'Subscriber Already Exists',
        ];
    } 
}