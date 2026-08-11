<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AudioRequest extends FormRequest
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
        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description')) ;
        });

        $rules = [
            //
            'name'          =>  'required|max:20',
            'description'   =>  'nullable|max:100',
            'audio_type'    =>  'required',
        ];
       /* if(request('audio_type') == 'attach')
        {
            $rules['audioupload'] = 'required|mimes:mp3|max:8092';
        }*/
        if(request('audio_type') == 'record')
        {
            $rules['audioupload'] = 'required|mimes:mp3';
        }
        if(request('audio_type') == 'attach')
        {
            $rules['audios'] = 'required|mimes:mp3';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            //
            'name.required'                 =>  'Title Is Required',
            'name.max:20'                   =>  'Title Cannot Be More Than 20 Characters',

            'description.check_description' =>  'Enter Valid Description',
            'description.max'               =>  'Description Cannot Be More Than 100 Characters',

            'audio_type.required'           =>  'Audio Type Is Required',

            'audioupload.required'          =>  'Audio File Is Required',
            'audioupload.mimes'             =>  'Audio File Should Be mp3',
            'audioupload.max'               =>  'Audio File Should Not Be Greater Than 8 MB',
        ];
    }
}