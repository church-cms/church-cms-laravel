<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\PostCategory;

class PostCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $editId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($editId) {
                    $query = PostCategory::where('church_id', Auth::user()->church_id)
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
            'description' => 'nullable|string|max:500',
            'status'      => 'nullable|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category Name is required.',
            'name.max'      => 'Category Name cannot exceed 100 characters.',
        ];
    }
}
