<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PayaccountAddRequest extends FormRequest
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
        $rules=[
            'paymentgateway_id'=>'required',
            'status'=>'required',   
        ];

        if(request('paymentgateway_id')=='bank')
        {
            $rules=[
                'account_name'  =>'required',
                'account_number'=>'required|numeric',
                'ifsc_code'  =>'required',
                'branch_name'  =>'required',
                'bank_name'  =>'required',
                'branch_address'  =>'required',
            ];
        }

        if(request('paymentgateway_id')=='gpay')
        {
            $rules['gpay_number']='required|numeric';
        }

        if(request('paymentgateway_id')=='upi')
        {
            $rules['upi_id']='required';
        }

        if(request('paymentgateway_id')=='cheque')
        {
            $rules['payee_name']='required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            //
            'paymentgateway_id.required'        =>  'Please Select PaymentGateway',
            'status.required'                   =>  'Please Select Status',
            'account_name.required'             =>  'Please Enter Account Holder Name',
            'account_number.required'           =>  'Please Enter Account Number',
            'ifsc_code.required'                =>  'Please Enter IFSC code',
            'branch_name.required'              =>  'Please Enter Branch name',
            'bank_name.required'                =>  'Please Enter Bank name',
            'branch_address.required'           =>  'Please Enter Branch address',
            'gpay_number.required'              =>  'Please Enter Gpay Number',
            'upi_id.required'                   =>  'Please Enter UPI id',
            'payee_name.required'               =>  'Please Enter Payee Name',
        ];
    }
}
