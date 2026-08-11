<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Keyword;
use App\Models\Church;
use App\Models\User;
use App\Models\Country;

class RegisterStepOneRequest extends FormRequest
{
    // public $country_id=Country::where('short_name','IN')->first()->id;
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
        $country=Country::where('short_name','IN')->where('status',1)->first();

        Validator::extend('check_unique_church_name',function($attribute,$value,$parameters,$validator)
        {
            $slug   = Str::slug(request('church_name'),'-');
            $church = Church::where('slug','=',$slug)->exists();
            if($church)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_keyword',function($attribute,$value,$parameters,$validator)
        {
            $keywords = Keyword::pluck('name')->toArray();
            $flag = false;
            foreach ($keywords as $keyword) 
            {
                $string = str_contains(request('church_name'),$keyword);
                if($string)
                {
                    $flag = true;
                    break;
                }
            }
            if ($flag == true) 
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_address',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address'));
        });
        $rules = [
            'church_name'           =>  'required|max:30|check_unique_church_name', //check_keyword
            'address'               =>  'required|check_address',
            // 'city_id'               =>  'required',
            // 'state_id'              =>  'required',
            // 'pincode'               =>  'required',
            // 'country_id'            =>  'required',  
        ];
        // // $rules = ['address' =>  'required|check_address'];
        $rules['country_id'] = ['required'];
        
        if($country!=null)
        {
            if($country->id==request('country_id'))
            {
            $rules['city_id'] =  ['required'];
            $rules['state_id'] =  ['required'];
            }

        }
        

        $rules['pincode'] = ['required'];
        
        return $rules;
    }

    public function messages()
    {
        return[
            'church_name.required'                  => 'Church Name is required',
            'church_name.max:30'                    => 'Church Name should be atmost 30 digits',
            'church_name.check_unique_church_name'  => 'Church Name already exists. Try different Church Name',
            'church_name.check_keyword'             => 'Enter a Valid Church Name',

            'address.required'                      => 'Address is required',
            'address.check_address'                 => 'Enter a Valid Address',

            'city_id.required'                      => 'City is required',

            'state_id.required'                     => 'State is required',

            'pincode.required'                      => 'Pincode is required',
        ];
    }
}