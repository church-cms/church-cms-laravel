<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Fund;

class FundRequest extends FormRequest
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

        Validator::extend('check_payee_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('payee_name')) ;
        });

        Validator::extend('check_payable_at',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('payable_at')) ;
        });

        Validator::extend('check_bank_name', function ($attribute,$value,$parameters,$validator) 
        {
            //return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('bank_name')); 
            return preg_match('/^[A-Za-z0-9\s]+$/', request('bank_name')); 
        });

        Validator::extend('check_card_name', function ($attribute,$value,$parameters,$validator) 
        {
            //return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('card_name')); 
            return preg_match('/^[A-Za-z0-9\s]+$/', request('card_name')); 
        });

        Validator::extend('check_comments', function ($attribute,$value,$parameters,$validator) 
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('comments')); 
        });

        Validator::extend('check_account_number', function ($attribute,$value,$parameters,$validator) 
        {
            if(strlen(request('account_number')) == 12)
            {
                return true; 
            }
            return false;
        });

        Validator::extend('check_pan_number', function ($attribute,$value,$parameters,$validator) 
        {
            if(strlen(request('pan_number')) == 10)
            {
                return true; 
            }
            return false;
        });

        $rules=
        [
            'membership' => 'required',
            'method'     => 'required',
            'amount'     => 'required',
            'status'     => 'required',
        ];
        if(request('membership') == "member")
        {
            $rules['selectedUsers']   = 'required';  
        }
        if(request('membership') == "guest")
        {
            $rules['first_name']      = 'required|check_first_name|max:15';
            $rules['last_name']       = 'required|check_last_name|max:15';
            $rules['address']         = 'required|check_address|max:30';
            $rules['mobile_number']   = 'required|numeric|digits:10';
        }

        if(request('method') == "cheque")
        {
            $rules['cheque_number']  = 'required|numeric|digits:6';
            $rules['account_number'] = 'required|alpha_num|check_account_number';
            $rules['payee_name']     = 'required|check_payee_name|max:20';
        }

        elseif(request('method') == "card")
        {
            $rules['bank_name']    = 'required|check_bank_name|max:20';
            $rules['card_name']    = 'required|check_card_name|max:20';
        }

        elseif(request('method') == "demanddraft")
        {
            $rules['payable_at']     = 'required|check_payable_at|max:20';
            $rules['account_number'] = 'required|alpha_num|check_account_number'; 
        }

        if(request('amount') >= '100000')
        {
            $rules['pan_number']      = 'required|alpha_num|check_pan_number';
        }

        if(request('status') == 'cancel')
        {
            $rules['comments']         = 'required|check_comments|max:30';
        }
        return $rules;
    }

    public function messages()
    {
        return[
            'membership.required'                   => 'Membership Type Is Required',

            'selectedUsers.required'                => 'Member Is Required',

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

            'pan_number.required'                   => 'PAN Number Is Required',
            'pan_number.alpha_num'                  => 'PAN Number Should Be Letters And Numbers',
            'pan_number.digits:10'                  => 'PAN Number Should Be 10 Digits',

            'amount.required'                       => 'Amount Is Required',

            'method.required'                       => 'Method Is Required',

            'cheque_number.required'                => 'Cheque Number Is Required',
            'cheque_number.numeric'                 => 'Enter Valid Cheque Number',
            'cheque_number.digits:10'               => 'Cheque Number Should Be 6 Digits',

            'account_number.required'               => 'Account Number Is Required',
            'account_number.alpha_num'              => 'Account Number Should Be Letters And Numbers',
            'account_number.check_account_number'   => 'Account Number Should Be 12 Digits',

            'payee_name.required'                   => 'Payee Name Is Required',
            'payee_name.check_payee_name'           => 'Enter Valid Payee Name',

            'payable_at.required'                   => 'Payable At Is Required',
            'payable_at.check_payable_at'           => 'Enter Valid Payable At',
            'payable_at.max'                        => 'Payable At May Not Be Greater Than 20 Characters',

            'bank_name.required'                    => 'Bank Name Is Required',
            'bank_name.check_bank_name'             => 'Enter Valid Bank Name',
            'bank_name.max'                         => 'Bank Name May Not Be Greater Than 20 Characters',

            'card_name.required'                    => 'Card Name Is Required',
            'card_name.check_card_name'             => 'Enter Valid Card Name',
            'card_name.max'                         => 'Card Name May Not Be Greater Than 20 Characters',

            'status.required'                       => 'Status Is Required',

            'comments.required'                     => 'Comments Is Required',
            'comments.check_comments'               => 'Enter Valid Comments',
            'comments.max'                          => 'Comments May Not Be Greater Than 30 Characters',
        ];
    }
}