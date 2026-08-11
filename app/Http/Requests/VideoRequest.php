<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class VideoRequest extends FormRequest
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
        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description')) ;
        });

        Validator::extend('check_url', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/',request('videourl'));     
        });

        $rules = [
            //
            'name'          =>  'required|max:20',
            'description'   =>  'nullable|check_description|max:100',
            'video_type'    =>  'required',
        ];

        if(request('video_type') == 'video_url')
        {
            $rules['videourl'] = 'required|check_url';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            //
            'name.required'                 =>  'Title Is Required',
            'name.max:20'                   =>  'Title Cannot Be More Than 20 Characters',

            'description.check_description' =>  'Enter Valid Description',
            'description.max'               =>  'Description Cannot Be More Than 100 Characters',

            'video_type.required'           =>  'Video Type Is Required',

            'videourl.required'             =>  'Video Url Is Required',
            'videourl.check_url'            =>  'Enter Valid Video Url',
        ];
    } 
}