<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventRequest extends FormRequest
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
     public function rules(Request $request)
   {
     
      Validator::extend('checklocation',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('location')) ;
        });
        Validator::extend('check_location', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('location'));  
        });


      Validator::extend('check_freq', function ($attribute, $value, $parameters, $validator) {

        $freq=request('freq');
        //dd($freq);
        if($freq=='0')
        {
          return FALSE;
        }
        return TRUE;
      });


      Validator::extend('check_freq_term', function ($attribute, $value, $parameters, $validator) {

        $freq_term=request('freq_term');
        //dd($freq_term);
        if($freq_term=='0')

        {
          //dd($freq_term);
          return FALSE;
        }
        return TRUE;
      });




    Validator::extend('alpha_spaces', function ($attribute, $value,$parameters,$validator) {

              // This will only accept alpha and spaces. 
              // If you want to accept hyphens use: /^[\pL\s-]+$/u.
              return preg_match('/^[\pL\s]+$/u', request('title')); 
            });
        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('title'));  
        });


    Validator::extend('check_start_date', function ($attribute, $value, $parameters, $validator) {

       if( date('Y-m-d',strtotime(request('start_date'))) >date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d')))))
        //dd(date('Y-m-d',strtotime('-1 days',strtotime('Y-m-d'))));

       {
          return true;
       }
          return false;
      });



    $rules=
       [
          'title'      => 'required|max:100|check_title',
          'description'=> 'required|max:255',
          'repeats'    => 'required',
          'location'   => 'required|check_location',
          'category'   => 'required',
          'organised_by'=>'required',
          //'image'      => 'required|mimes:jpg,png,jpeg',
          'start_date' => 'required|before_or_equal:end_date|check_start_date',
          'end_date'   => 'required|after_or_equal:start_date',

       ];
        if(request('repeats')=="1")
       {
          $rules['freq']      = 'required|check_freq';
          $rules['freq_term'] = 'required|check_freq_term';

       }
       //dd(Input::get('freq'));
      return $rules;
      
   }

   public function messages()
   {
       return
       [
          'title.required'               => 'Title is required!',
          'title.check_title'           => 'Enter only alphabets',
          'description.required'         => 'Enter Description',
          'repeats.required'             => 'Select repeats..',
          'freq.required'                => 'Select freq',
          'freq_term.required'           => 'Select freq term',
          'freq.check_freq'              => 'select freq',
          'freq_term.check_freq_term'    => 'select freq term',
          'location.required'            => 'Enter Location',
          'location.check_location'       => 'Invalid location',
          'category.required'            => 'Select Category',
          'organised_by.required'        => 'Enter Organiser Name',
          'image.required'               => 'Upload Image',
          'image.mimes'                  => 'File extension error',
          'start_date.after'             => 'Please select upcoming date',
          'start_date.required'          => 'Start Date is required!',
          'start_date.check_start_date'  => 'Start Date should be after yesterday',
          'end_date.required'            => 'End Date is required!',
          'end_date.checkunique_end'     => 'End Date should be after start date',
           
       ];
}
}






