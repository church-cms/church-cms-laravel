<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Faq;

class FaqUpdateRequest extends FormRequest
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
        Validator::extend('check_question',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:"?(\)\s]+$/', $attribute);
        });
        
        Validator::extend('check_answer', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:;(\)\s]+$/', $attribute);  
        });
        
        Validator::extend('check_order', function ($attribute, $value, $parameters, $validator) 
        {   
            $faq = Faq::where([['faq_category_id',request('category')],['order',request('order')],['id','!=',request('id')]])->first();
            if($faq != null)
            {
                return false;
            } 
            return true;
        });

        return [
            //
            'category'  =>  'required',
            'question'  =>  'required|check_question',
            'answer'    =>  'required|check_answer',
            'order'     =>  'nullable|check_order',
        ];
    }

    public function messages()
    { 
        return[
            //
            'category.required'         =>  'Faq Category Is Required',

            'question.required'         =>  'Question Is Required',
            'question.check_question'   =>  'Enter Valid Question',

            'answer.required'           =>  'Answer Is Required',
            'answer.check_answer'       =>  'Enter Valid Answer',

            'order.check_order'         =>  'Order Already Exists For This Faq Category',
        ]; 
    }
}