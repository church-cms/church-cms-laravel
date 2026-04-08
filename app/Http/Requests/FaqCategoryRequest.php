<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\FaqCategory;

class FaqCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // On update the route has {id}, on store it doesn't
        $editId = $this->route('id');

        return [
            'name'   => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9_~\-!@#\$%\^&*.,:"?(\)\s]+$/',
                function ($attribute, $value, $fail) use ($editId) {
                    $query = FaqCategory::where('church_id', Auth::user()->church_id)
                        ->where('status', 1)
                        ->whereRaw('LOWER(name) = ?', [strtolower($value)]);

                    if ($editId) {
                        $query->where('id', '!=', $editId);
                    }

                    if ($query->exists()) {
                        $fail('Category name already exists.');
                    }
                },
            ],
            'status' => 'nullable|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category Name Is Required',
            'name.max'      => 'Category Name Cannot Be More Than 30 Characters',
            'name.regex'    => 'Enter Valid Category Name',
        ];
    }
}
