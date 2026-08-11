<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Quote;

class QuoteRescheduleRequest extends FormRequest
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
        Validator::extend('check_reschedule_date', function ($attribute, $value, $parameters, $validator) 
        {   
            $today = date('Y-m-d H:i:s');
            if( date('Y-m-d H:i:s',strtotime(request('reschedule_date'))) > $today )
            {
                return true;
            }
            return false;  
        });
        
        Validator::extend('exist_reschedule_date', function ($attribute, $value, $parameters, $validator) 
        {   
            $today = date('Y-m-d H:i:s');
            $quotes = Quote::where([['church_id',Auth::user()->church_id],['publish_on','>',$today]])->get();
            if(count($quotes) > 0)
            {
                foreach ($quotes as $quote) 
                {
                    if( date('Y-m-d H:i:s',strtotime($quote->publish_on)) ==  date('Y-m-d H:i:s',strtotime(request('reschedule_date'))) )
                    {
                        return false;
                    }
                    return true;  
                }
            }
            return true;
        });

        return [
            //
            'reschedule_date'  =>  'required|check_reschedule_date|exist_reschedule_date',
        ];
    }

    public function messages()
    {
        return[
            //
            'reschedule_date.required'              =>  'Reschedule Date Is Required',
            'reschedule_date.check_reschedule_date' =>  'Reschedule Date Should Be Greater Than Current Date',
            'published_at.exist_reschedule_date'    =>  'Quote Already Exist For This Date',
        ];
    }
}