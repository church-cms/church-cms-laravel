<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqList extends JsonResource
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
            'id'        =>  $this->id,
            'category'  =>  ucwords($this->faqCategory->name),
            'question'  =>  $this->question,
            'answer'    =>  $this->answer,
            'order'     =>  $this->order == null ? '--':$this->order,
        ];
    }
}