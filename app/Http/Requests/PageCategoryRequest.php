<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PageCategoryRequest extends FormRequest
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
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'status'      => 'nullable|in:0,1',
        ];
    }

    public function messages()
    {
        return[
            //
            'name.required'     =>  'Name Is Required',
            'name.check_name'   =>  'Enter Valid Name',
        ];
    }
}
