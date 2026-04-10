<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class BulletinRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/',request('name'));  
        });

        $rules= [
            //
            'name'          => 'required|max:15|check_name',
            'type'          => 'required',
            'cover_image'   => 'nullable|mimes:jpg,jpeg,png,webp',
            'year'          => 'required',
            'path'          => 'required|mimes:pdf|max:8092',  
        ];

         if(request('type') == "week")
        {
            $rules['week']='required';
        }
        else
        {
            $rules['month']='required';
        }
        return $rules;
    }

    public function messages()
    {
        return[
            'name.required'         => 'Bulletin Name is required',
            'name.max:15'           => 'Bulletin Name should be atmost 15 digits',
            'name.check_name'       => 'Enter Valid Bulletin Name',

            'type.required'         => 'Type is required',

            'week.required'         => 'Week is required',

            'month.required'        => 'Month is required',

            'year.required'         => 'Year is required',

            'cover_image.required'  => 'Cover Image is required',
            'cover_image.mimes'     => 'Choose jpg,jpeg,png,webp file',

            'path.required'         => 'Bulletin File is required',
            'path.mimes'            => 'Choose a pdf file', 
            'path.max'              => 'Maximum file size to upload is 8MB',    
        ];
    }
}
