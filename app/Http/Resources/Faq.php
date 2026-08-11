<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Faq extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // 
            'category'  =>  ucwords($this->faqCategory->name),
            'title'     =>  $this->question,
            'value'     =>  $this->answer,
        ];
    }
}