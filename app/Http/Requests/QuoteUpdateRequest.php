<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Quote;

class QuoteUpdateRequest extends FormRequest
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
        Validator::extend('check_text', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('text'));  
        });
        
        Validator::extend('check_tamil', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('tamil'));  
        });
        
        Validator::extend('check_english', function ($attribute, $value, $parameters, $validator) 
        {   
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('english'));  
        });
        
        Validator::extend('check_publish_on', function ($attribute, $value, $parameters, $validator) 
        {   
            $today = date('Y-m-d H:i:s');
            if( date('Y-m-d H:i:s',strtotime(request('publish_on'))) > $today )
            {
                return true;
            }
            return false;  
        });
        
        Validator::extend('exist_publish_on', function ($attribute, $value, $parameters, $validator) 
        {   
            $today = date('Y-m-d H:i:s');
            $quotes = Quote::where([['church_id',Auth::user()->church_id],['publish_on','>',$today]])->get();
            if(count($quotes) > 0)
            {
                foreach ($quotes as $quote) 
                {
                    if( date('Y-m-d H:i:s',strtotime($quote->publish_on)) ==  date('Y-m-d H:i:s',strtotime(request('publish_on'))) )
                    {
                        return false;
                    }
                    return true;  
                }
            }
            return true;
        });

        $rules ['publish_on']  =  'required|check_publish_on|exist_publish_on';
        if(request('tab') == 'images')
        {
            if(request('image') != null)
            {
                $rules['image']  = 'nullable|mimes:jpg,jpeg,png,webp';
            }
        }
        elseif (request('tab') == 'text') 
        {
            $rules['text']     =   'required|check_text|max:500';
        }
        elseif (request('tab') == 'bible') 
        {
            $rules['tamil']     =   'required|check_tamil|max:250';
            $rules['english']   =   'required|max:250';//|check_english
        }
        return $rules;
    }

    public function messages()
    { 
        return[
            //
            'image.required'                    =>  'Image Is Required',
            'image.mimes'                       =>  'Choose jpg,jpeg,png,webp file',

            'text.required'                     =>  'Text Required',
            'text.check_text'                   =>  'Enter Valid Text',
            'text.max'                          =>  'Text Cannot Be More Than 500 Characters',

            'tamil.required'                    =>  'Tamil Quotes Required',
            'tamil.check_tamil'                 =>  'Enter Valid Tamil Quotes',
            'tamil.max'                         =>  'Tamil Quotes Cannot Be More Than 250 Characters',

            'english.required'                  =>  'English Quotes Required',
            'english.check_english'             =>  'Enter Valid English Quotes',
            'english.max'                       =>  'English Quotes Cannot Be More Than 250 Characters',

            'publish_on.required'               =>  'Publish Date Is Required',
            'publish_on.check_publish_on'       =>  'Publish Date Should Be Greater Than Current Date',
            'publish_on.exist_publish_on'       =>  'Quote Already Exist For This Date',
        ]; 
    }
}