<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class HelpUpdateRequest extends FormRequest
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
        Validator::extend('check_comments', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/',request('comments'));  
        });

        $rules = [
            'status' => 'required',
        ];

        if( request('status') == 'approve')
        {
            $rules['expired_at']='required';
        }
        elseif( request('status') == 'reject')
        {
            $rules['comments'] = 'required|check_comments|max:30';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'status.required'           =>  'Status Is Required',

            'expired_at.required'       =>  'Expired At Is Required',

            'comments.required'         =>  'Comments Is Required', 
            'comments.check_comments'   =>  'Enter Valid Comments',
        ];
    }
}