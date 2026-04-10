<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class DetailRequest extends FormRequest
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
        Validator::extend('check_short_summary',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/\pL\pM*|./u', request('short_summary')) ;
        });

        Validator::extend('check_long_summary',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/\pL\pM*|./u', request('long_summary')) ;
        });

        Validator::extend('check_quotes',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/\pL\pM*|./u', request('quotes')) ;
        });

        Validator::extend('check_address',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address')) ;
        });

        Validator::extend('check_facebook',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', request('facebook'));
        });

        Validator::extend('check_twitter',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:http:\/\/)?(?:www\.)?twitter\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-]*)/', request('twitter'));
        });

        Validator::extend('check_instagram',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/(?:(?:http|https):\/\/)?(?:www\.)?(?:instagram\.com|instagr\.am)\/([A-Za-z0-9-_\.]+)/im', request('instagram'));
        });

        $rules = [
            'short_summary' =>  'required|check_short_summary|max:500',
            'long_summary'  =>  'required|check_long_summary|max:2000',
            'quotes'        =>  'nullable|check_quotes|max:200',
            'phone'         =>  'nullable|numeric|digits:10',
            'email'         =>  'nullable|email',
           // 'address'       =>  'nullable|check_address', 
            'address'       =>  'nullable', 
            'facebook'      =>  'nullable|check_facebook',
            'twitter'       =>  'nullable|check_twitter',
            'instagram'     =>  'nullable|check_instagram',
            'website'       =>  'nullable|url'
        ];

        if(request('church_logo')!= '')
        {
            $rules['church_logo'] = 'nullable|mimes:jpg,jpeg,png,webp';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [ 
            'short_summary.required'            =>  'Short Summary Required',
            'short_summary.check_short_summary' =>  'Enter Valid Short Summary',
            'short_summary.max'                 =>  'Short Summary Should Not Exceed more than 500 words',

            'long_summary.required'             =>  'Long Summary Required',
            'long_summary.check_long_summary'   =>  'Enter Valid Long Summary',
            'long_summary.max'                  =>  'Long Summary Should Not Exceed more than 2000 words',

            'quotes.required'                   =>  'Quotes Required',
            'quotes.check_quotes'               =>  'Enter Valid Quotes',

            'phone.numeric'                     =>  'Phone Should Be Number',
            'phone.digits'                      =>  'Phone Cannot Be More Than 10 Digits',
            
            'email.email'                       =>  'Enter Valid Email',

            'address.check_address'             =>  'Enter Valid Address',

            'facebook.check_facebook'           =>  'Enter Valid Facebook URL',

            'twitter.check_twitter'             =>  'Enter Valid Twitter URL',

            'instagram.check_instagram'         =>  'Enter Valid Instagram URL',

            'website.url'                       =>  'Enter Valid Website',

            'church_logo.mimes'                 =>  'Choose jpg,jpeg,png,,webp File',
        ];
    }
}