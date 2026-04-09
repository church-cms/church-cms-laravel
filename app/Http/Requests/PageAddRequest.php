<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageAddRequest extends FormRequest
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
            'page_name'        => 'required|string|max:255',
            'category'         => 'required',
            'description'      => 'required',
            'slug'             => 'nullable|string|max:255|regex:/^[a-z0-9\-]+$/',
            'cover_image'      => 'nullable|max:2048|mimes:jpg,jpeg,png',
            'menu_text'        => 'nullable|string|max:80',
            'menu_order'       => 'nullable|integer|min:0',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'page_name.required'   => 'Page Name is required',
            'page_name.max'        => 'Page Name cannot exceed 255 characters',
            'category.required'    => 'Category is required',
            'description.required' => 'Description is required',
            'slug.regex'           => 'Slug may only contain lowercase letters, numbers and hyphens',
            'cover_image.mimes'    => "Cover Photo must be JPG or PNG",
            'cover_image.max'      => 'Cover Photo must be under 2MB',
        ];
    }
}
