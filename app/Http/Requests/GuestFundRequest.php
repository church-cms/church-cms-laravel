<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class GuestFundRequest extends FormRequest
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
        Validator::extend('check_first_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('first_name')) ;
        });

        Validator::extend('check_last_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('last_name')) ;
        });

        Validator::extend('check_address',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address')) ;
        });

        return [
            'amount'        => 'required',
            'first_name'    => 'required|check_first_name|max:15',
            'last_name'     => 'required|check_last_name|max:15',
            'address'       => 'required|check_address|max:30',
            'mobile_number' => 'required|numeric|digits:10',
            'payaccount_id' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'first_name.required'                   => 'First Name Is Required',
            'first_name.check_first_name'           => 'Enter Valid First Name',
            'first_name.max'                        => 'First Name May Not Be Greater Than 15 Characters',

            'last_name.required'                    => 'Last Name Is Required',
            'last_name.check_last_name'             => 'Enter Valid Last Name',
            'last_name.max'                         => 'Last Name May Not Be Greater Than 15 Characters',

            'address.required'                      => 'Address Is Required',
            'address.check_address'                 => 'Enter Valid Address',
            'address.max'                           => 'Address May Not Be Greater Than 30 Characters',

            'mobile_number.required'                => 'Mobile Number Is Required',
            'mobile_number.numeric'                 => 'Mobile Number Should Be Numeric',
            'mobile_number.digits:10'               => 'Mobile Number Should Be 10 Digits',

            'amount.required'                       => 'Amount Is Required',
            'payaccount_id.required'                => 'Please Select Payment method',
        ];
    }
}