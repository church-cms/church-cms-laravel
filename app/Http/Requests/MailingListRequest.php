<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class MailingListRequest extends FormRequest
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
            return preg_match('/^[A-Za-z\s]+$/', request('name')) ;
        });

        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description')) ;
        });

        return [
            'name'          =>  'required|check_name',
            'description'   =>  'required|check_description|max:250'
        ];
    }

    public function messages()
    {   
        return [
            'name.required'                 => 'Title Is Required',
            'name.check_name'               => 'Enter Valid Title',

            'description.required'          => 'Description Is Required',
            'description.check_description' => 'Enter Valid Description',
            'description.max'               => 'Description Cannot Be More Than 250 Characters',
        ];
    } 
}