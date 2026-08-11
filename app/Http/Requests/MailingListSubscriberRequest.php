<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailingListSubscriberRequest extends FormRequest
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
            'mailinglist_id'    =>  'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'mailinglist_id.required'   =>  'Mailing List Is Required',
        ];
    }
}