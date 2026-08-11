<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CampaignRequest extends FormRequest
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
            return preg_match('/^[\sA-Za-z0-9_-]+$/', request('name'));
        });

        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description'));
        });

        return [
            'name'          => 'required|max:30|check_name',
            'description'   => 'nullable|max:250|check_description',
        ];
    }

    public function messages()
    {
        return[
            'name.required'                 => 'Title Is Required',
            'name.max'                      => 'Title Cannot Be More Than 30 Characters',
            'name.check_name'               => 'Enter Valid Title',

            'description.max'               => 'Description Cannot Be More Than 30 Characters',
            'description.check_description' => 'Enter Valid Description',
        ];
    }
}