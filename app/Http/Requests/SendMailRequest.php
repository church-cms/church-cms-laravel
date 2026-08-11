<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SendMailRequest extends FormRequest
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
        Validator::extend('check_subject', function ($attribute, $value, $parameters, $validator) 
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/',request('subject'));  
        });

        Validator::extend('check_message', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('message')); 
        });

        Validator::extend('check_executed_at', function ($attribute, $value, $parameters, $validator) 
        {   
            $today = date('Y-m-d H:i:s');
            $date = date('Y-m-d H:i:s',strtotime(request('executed_at')));
            if($date > $today)
            {
                return true;
            } 
            return false;
        });

        $rules = [
            //
            'mode'          => 'required',
        ];

        if(request('mode') == 'mail')
        {
            $rules ['subject']      =  'required|max:30|check_subject';
            $rules ['message']      =  'required|max:1000|check_message';
            $rules ['attachments']  =  'nullable|mimes:pdf,jpg,jpeg,png,doc,csv,docx';
        }
        elseif(request('mode') == 'notification')
        {
            $rules ['message']      =  'required|max:1000|check_message';
        }
        elseif(request('mode') == 'sms')
        {
            $rules ['message']      =  'required|max:300|check_message';
        }

        if(request('send_later') == 'true')
        {
            $rules['executed_at'] = 'required|check_executed_at';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'subject.required'              =>  'Subject Is Required',
            'subject.check_subject'         =>  'Enter Valid Subject',
            'subject.max:30'                =>  'Subject Cannot Be More Than 30 Characters',

            'message.required'              =>  'Message Is Required',
            'message.check_message'         =>  'Enter Valid Message',
            'message.max:1000'              =>  'Message Cannot Be More Than 1000 Characters',
            'message.max:300'               =>  'Message Cannot Be More Than 300 Characters',

            'attachments.mimes'             =>  'Choose Proper File.The Required Extensions Are :".pdf",".jpg",".jpeg",".png",".doc",".csv",".docx" ', 

            'executed_at.required'          =>  'Date Time Is Required',
            'executed_at.check_executed_at' =>  'Date Time Should Be Greater Than Today',    
        ];
    }
}