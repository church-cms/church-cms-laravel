<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ImportMemberRequest extends FormRequest
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
        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator)
        {
            $extension = $value->getClientOriginalExtension();
            return $extension != '' && in_array($extension, $parameters);
        });

        return [
            //
            'import_file' => 'required|file_extension:csv',
        ];
    }

     public function messages()
    {
        return[
            'import_file.required'          => 'File is required',
            'import_file.file_extension'    => 'Choose csv file',
            'import_file.max'               => 'Maximum file size to upload is 2MB',
            ];
        }
}
