<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PostRequest extends FormRequest
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
        Validator::extend('check_title', function ($attribute, $value, $parameters, $validatior) {
            return preg_match('/^[A-Za-z\s]+$/', $attribute);
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validatior) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_posted_at', function ($attribute, $value, $parameters, $validator) {
            if (request('posted_at') > date('d-m-Y H:i:s')) {
                return true;
            }
            return false;
        });

        $rules = [
            //
            'title'         =>  'required|max:150|check_title',
            'description'   =>  'required|max:5000|check_description',
            'category'         => 'required',
            //'visibility'    =>  'required',
        ];

        if (request('visibility') == 'select_class') {
            $rules['visible_for']   =   'required';
        }

        if (request('post_later') == 'true') {
            $rules['posted_at'] = 'check_posted_at';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            //
            'title.required'                =>  'Title is required',
            'title.max'                     =>  'Title cannot be more than 30 characters',
            'title.check_title'             =>  'Enter Valid Title',

            'description.required'          =>  'Description is required',
            'description.max'               =>  'Description cannot be more than 500 characters',
            'description.check_description' =>  'Enter Valid Description',

            'visibility.required'           =>  'Visibility is required',

            'visible_for.required'          =>  'Select Class is required',

            'posted_at.check_posted_at'     =>  'Enter Future Date Time',

            'category.required'    => 'Category is required',
        ];

        return $messages;
    }
}
