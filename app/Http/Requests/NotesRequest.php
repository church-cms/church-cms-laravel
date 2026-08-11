<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class NotesRequest extends FormRequest
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
        Validator::extend('checknotes', function ($attribute, $value, $parameters, $validator) 
        {   
           return preg_match('/^\p{L}[\p{L} A-Za-z0-9_~\-!,@#\$%\^&*.:(\)((?:\'|").*(?:\'|"))\s]+$/u',request('notes'));      
        });

        Validator::extend('check_notes', function ($attribute, $value, $parameters, $validator) 
        {   
            //validation for tamil letters
            return preg_match('/\pL\pM*|./u',request('notes'));  
        });

        return [
             'notes' => 'required|check_notes',
            
        ];
    }

    public function messages()
    {    
        return
        [ 
           'notes.required' =>__('notes.notes_required'),
           'notes.check_notes' =>__('notes.notes_checknotes'),  
        ];
    }
}