<?php

namespace App\Http\Resources\API\Angular;

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
        return[
            //
            'id'        =>  $this->id,
            'category'  =>  ucwords($this->faqCategory->name),
            'category_id'  =>  $this->faq_category_id,
            'question'  =>  $this->question,
            'answer'    =>  $this->answer,
            'order'     =>  $this->order,
        ];
    }
}