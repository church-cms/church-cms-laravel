<?php

namespace App\Http\Requests\Api\Guest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class FeedbackRequest extends FormRequest
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
        Validator::extend('check_mob', function ($attribute, $value, $parameters, $validator)
        {
            $user=User::where([['church_id',request('church_id')],['mobile_no',request('mobile_no')]])->first();
            //dd(count($user));
            if(count($user)==0)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_message',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('message')) ;
        });

        Validator::extend('check_count',function($attribute,$value,$parameters,$validator) 
        {
            $file_count = count(request('files'));
            if($file_count > 6)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_file_extension', function ($attribute, $value, $parameters, $validator)
        {
            $extension=$value->getClientOriginalExtension();
            return $extension != '' && in_array($extension, $parameters);
        });

         

        $rules = [
            //
            'church_id' =>  'required',
           // 'mobile_no' =>  'required|check_mob',
            'mobile_no' =>  'required|min:10',
            'message'   =>  'required|max:300|check_message',
            'category'  =>  'required',//|check_count
        ];

        //$files = request('files');
        $rules['files.*'] = 'nullable|check_file_extension:jpeg,jpg,png|max:2048';
        
        return $rules;
    }

    public function messages()
    {
        return [
            //
            'message.required'      =>  'Enter Message',
            'message.max'           =>  'Message cannot be more than 300 characters',
            'message.check_message' =>  'Enter Valid Message',

            'category.required'     =>  'Category Is Required',
            'mobile_no.required'    =>  'Required',
            'mobile_no.check_mob'    =>  'Your Number not register.Please fill guest form',

            'category.check_count'  =>  'Attachment cannot be more than 6',
            'files.max'             =>  'Attachment size should be within 2MB',
            'files.*'               =>  "Attachment should be 'JPG or PNG'",
        ];
    }
}