<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SeoDetailBasicRequest extends FormRequest
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
        Validator::extend('check_sitetitle',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sitetitle')) ;
        });

        Validator::extend('check_site_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('site_description')) ;
        });

        Validator::extend('check_site_keyword',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('site_keyword')) ;
        });

        Validator::extend('check_header_code',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('header_code')) ;
        });

        Validator::extend('check_footer_code',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('footer_code')) ;
        });

        return [
            // 'sitetitle'         =>  'required|check_sitetitle|max:25',
            // 'site_description'  =>  'required|check_site_description|max:100',
            // 'site_keyword'      =>  'required|check_site_keyword',
            'header_code'       =>  'required|check_header_code',
            'footer_code'       =>  'required|check_footer_code', 
        ];
    }

    public function messages()
    {
        return[
            'sitetitle.required'                        =>  __('sites.sitetitle'),
            'sitetitle.check_sitetitle'                 =>  __('sites.check_sitetitle'),
            'sitetitle.max'                             =>  __('sites.max_sitetitle'),

            'site_description.required'                 =>  __('sites.site_description'),
            'site_description.check_site_description'   =>  __('sites.check_site_description'),
            'site_description.max'                      =>  __('sites.max_site_description'),

            'site_keyword.required'                     =>  __('sites.site_keyword'),
            'site_keyword.check_site_keyword'           =>  __('sites.check_site_keyword'),

            'header_code.required'                      =>  __('sites.header_code'),
            'header_code.check_header_code'             =>  __('sites.check_header_code'),

            'footer_code.required'                      =>  __('sites.footer_code'),
            'footer_code.check_footer_code'             =>  __('sites.check_footer_code'),
        ];
    }
}