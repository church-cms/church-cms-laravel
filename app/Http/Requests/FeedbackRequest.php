<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class FeedbackRequest extends FormRequest
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
        Validator::extend('check_message', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('message'));
        });

        Validator::extend('check_count', function ($attribute, $value, $parameters, $validator) {
            $file_count = count(request('files'));
            if ($file_count > 6) {
                return false;
            }
            return true;
        });

        Validator::extend('check_file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();
            return $extension != '' && in_array($extension, $parameters);
        });

        // $rules = [
        //     'message'   =>  'required|max:300|check_message',
        //     'category'  =>  'required',//|check_count
        // ];

        $rules = [
            'message'  => 'required|max:300|check_message',
            'category' => 'required|in:bug,suggestion,others',
        ];

        $files = request('files');
        $rules['files.*'] = 'check_file_extension:jpeg,jpg,png|max:2048';

        return $rules;
    }
    public function messages()
    {
        return [
            //
            'message.required'      =>  'Enter Message',
            'message.max'           =>  'Message cannot be more than 300 characters',
            'message.check_message' =>  'Enter Valid Message',

            'category.required'     =>  'Category Is Required',
            'category.in'           => 'Please select a valid category',

            'category.check_count'  =>  'Attachment cannot be more than 6',
            'files.max'             =>  'Attachment size should be within 2MB',
            'files.*'               =>  "Attachment should be 'JPG or PNG'",
        ];
    }
}
