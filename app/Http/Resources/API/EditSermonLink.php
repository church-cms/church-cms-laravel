<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class EditSermonLink extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
          if($this->type=='document')
        {
            $url=$this->UrlPath;
        }
        else
        {
            $url= $this->url;
        }
       return [
            
            //'id'          => $this->id,
            'church_id'   => $this->church_id,
            'user_id'     => $this->user_id,
            'sermons_id'  => $this->sermons_id,
            'type'        => $this->type,
            'location'    => $this->location,
            'date'        => date('d-m-Y', strtotime($this->date)),
          //  'url'         => $this->UrlPath,
              'url'           =>  $url,
           

        ];
    }
}