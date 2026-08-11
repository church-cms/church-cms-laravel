<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestSendMailRequest extends FormRequest
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
            //
            'to_email'  =>  'required|email',
        ];
    }

    public function messages(){
        return [
            //
            'to_email.required' =>  'To Email Is Required',
            'to_email.email'    =>  'Enter Valid To Email',
        ];
    }
}