<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
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
            'page'    => 'required', 
            'content'    => 'required' 
        ];
    }

    public function messages()
    {
        return[
            'content.required'  => 'Content field is required',
             'page.required'  => 'Please select page' 
        ];
    }
}