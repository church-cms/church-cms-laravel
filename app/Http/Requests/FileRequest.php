<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('name'));  
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) 
        {   
          //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('description'));  
        });

          $file = $request->file('file');

           
          Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator)
            {
                $extension = $value->getClientOriginalExtension();
                return $extension != '' && in_array($extension, $parameters);
            });
                
               
                $j=count($file);
               
                  if($j==0)
                  {
                    $rules['file'] ='required';

                  }

             $rules['file.*'] = 'required|file_extension:doc,docm,docx,dot,dotm,dotx,pdf|max:8192';
              $rules['name'] ='required|check_name';
              $rules['description']='required|check_description';
           return $rules; 
        
    }

    public function messages()
    {
        
        return[
                  'name.required'=>'Name is Required',
            'name.check_name'      => 'Enter Valid Name',
                  'description.required'=>'Enter your description',
            'description.check_description'      => 'Enter Valid Description',
                 'file.required'=> 'Select atleast one file',
                 'file.*.file_extension' => 'File extension error',
                 'file.*.max' => "Maximum file size to upload is 8MB",
            ];
      
   } 
}


