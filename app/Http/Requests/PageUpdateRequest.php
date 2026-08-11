<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page_name'        => 'required|string|max:255',
            'category'         => 'required',
            'description'      => 'nullable|string',
            'slug'             => 'nullable|string|max:255|regex:/^[a-z0-9\-]+$/',
            'cover_image'      => 'nullable|max:2048|mimes:jpg,jpeg,png',
            'menu_text'        => 'nullable|string|max:80',
            'menu_order'       => 'nullable|integer|min:0',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'layout_template'  => 'nullable|in:no-sidebar,left-sidebar,right-sidebar',
        ];
    }

    public function messages()
    {
        return [
            'page_name.required'  => 'Page Name is required',
            'category.required'   => 'Category is required',
            'slug.regex'          => 'Slug may only contain lowercase letters, numbers and hyphens',
            'cover_image.mimes'   => 'Cover Photo must be JPG or PNG',
            'cover_image.max'     => 'Cover Photo must be under 2MB',
            'layout_template.in'  => 'Invalid layout template selected',
        ];
    }
}
