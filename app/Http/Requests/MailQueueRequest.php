<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class MailQueueRequest extends FormRequest
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
        Validator::extend('check_fired_at', function ($attribute, $value, $parameters, $validator) 
        {   
            $date = date('Y-m-d H:i:s',strtotime(request('fired_at')));
            $today = date('Y-m-d H:i:s');

            if($date > $today)
            {
                return true;
            }
            return false;  
        });

        return [
            'fired_at'  => 'required|check_fired_at',
        ];
    }

    public function messages()
    {
        return[
            'fired_at.required'         => 'Fired At Is Required',
            'fired_at.check_fired_at'   => 'Fired At Should Be Greater Than Today',
        ];
    }
}