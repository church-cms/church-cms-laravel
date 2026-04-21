<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SermonLinkUpdateRequest extends FormRequest
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
           $rules = [
                    'title'      => 'required|string|max:255',
                    'date'       => 'required|date',
                    'video_link' => 'nullable|url',
                    'audio_link' => 'nullable|url',
                    'pdf_link'   => 'nullable|file|mimes:pdf,doc,docx|max:20480',
                ];
                    /*else
                    {
                        $rules['url']    ='required|checkurl';
                    }*/




                    //|mimes:xls,xlsx,doc,docx,pdf,mp4

                 //|regex:^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$

             //$rules['file.*'] = 'required|file_extension:sql,xls,doc,docm,docx,dot,dotm,dotx,pdf,zip,txt,jpeg,jpg,bmp,png,mpeg,wav,ogg,mp4,webm,3gp,mov,flv,avi,gif,odt,wmv,mpga,http,https,www';

           /*  $rules['url.*'] = 'required|file_extension:sql,xls,doc,docm,docx,dot,dotm,dotx,pdf,txt,jpeg,jpg,bmp,png,mpeg,mp4,mov,flv,mpga';*/
           return $rules;

    }

    public function messages()
    {

        return[
                 'type.required'     => 'Select the file type',
                 'location.required' => 'Enter Location',
                 'date.required'     => 'Select Date',
                 'url.required'      => 'Select atleast one file',
                 'url.checkurl'      => 'Check url',
                 'url.mimes'         => 'file extension error',
                 //'url.regex'         => 'file error',
            ];

   }
}
