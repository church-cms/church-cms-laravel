<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'amount'=>'required|numeric',
            'payaccount_id'=>'required',
        ];
    }

    public function messages()
    {
        return[
            'amount.required'             => 'Please Enter amount',
            'payaccount_id.required'      => 'Please select payment method',
        ];
    }
}
