<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SeoDetailAdvancedRequest extends FormRequest
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
        Validator::extend('check_facebook_title',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('facebook_title')) ;
        });

        Validator::extend('check_facebook_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('facebook_description')) ;
        });

        Validator::extend('check_facebook_url',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', request('facebook_url'));
        });

        Validator::extend('check_twitter_title',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('twitter_title')) ;
        });

        Validator::extend('check_twitter_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('twitter_description')) ;
        });

        Validator::extend('check_twitter_url',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:http:\/\/)?(?:www\.)?twitter\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-]*)/', request('twitter_url'));
        });

        $rules =  [
            'facebook_title'        =>  'nullable|check_facebook_title|max:25',
            'facebook_description'  =>  'nullable|check_facebook_description|max:100',
            'facebook_url'          =>  'nullable',
            'twitter_title'         =>  'nullable|check_twitter_title|max:25', 
            'twitter_description'   =>  'nullable|check_twitter_description|max:100', 
            'twitter_url'           =>  'nullable', 
        ];

        if($request->facebook_image != null)
        {
            $rules['facebook_image'] = 'nullable|mimes:jpeg,jpg,png';
        }

        if($request->twitter_image != null)
        {
            $rules['twitter_image'] = 'nullable|mimes:jpeg,jpg,png';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'facebook_title.check_facebook_title'               =>  'Enter Valid Facebook Title',
            'facebook_title.max'                                =>  'Facebook Title Cannot Be More Than 25 Characters',

            'facebook_description.check_facebook_description'   =>  'Enter Valid Facebook Description',
            'facebook_description.max'                          =>  'Facebook Description Cannot Be More Than 100 Characters',

            'facebook_url.check_facebook_url'                   =>  'Enter Valid Facebook URL',

            'facebook_image.mimes'                              =>  'Facebook Image Should Be jpg,jpeg,png File',

            'twitter_title.check_twitter_title'                 =>  'Enter Valid Twitter Title',
            'twitter_title.max'                                 =>  'Twitter Title Cannot Be More Than 25 Characters',

            'twitter_description.check_twitter_description'     =>  'Enter Valid Twitter Description',
            'twitter_description.max'                           =>  'Twitter Description Cannot Be More Than 100 Characters',

            'twitter_url.check_twitter_url'                     =>  'Enter Valid Twitter URL',

            'twitter_image.mimes'                               =>  'Twitter Image Should Be jpg,jpeg,png File',
        ];
    }
}