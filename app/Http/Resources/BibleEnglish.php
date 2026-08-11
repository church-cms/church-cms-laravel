<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BibleEnglish extends JsonResource
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
            'book_id'            =>  $this->book_id, 
            'english_book'          =>  $this->english_book 
        ];
    }
}