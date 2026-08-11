<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteUploadRequest extends FormRequest
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
            //
                'quotes'  =>'required',
        ];
        return $rules;
    }

    public function messages()
    {   
      return
        [
          'quotes.required'=> 'Select atleast one file',
        ];
   } 
}