<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Quote;

class QuoteAddDateRequest extends FormRequest
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

        return [
            'publish_on'    =>  'required|check_publish_on|exist_publish_on',
        ];
    }

    public function messages()
    { 
        return[
            //
            'publish_on.required'               =>  'Publish Date Is Required',
            'publish_on.check_publish_on'       =>  'Publish Date Should Be Greater Than Current Date',
            'publish_on.exist_publish_on'       =>  'Quote Already Exist For This Date',
        ]; 
    } 
}