<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BibleTamil extends JsonResource
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
            'tamil_book'          =>  htmlspecialchars($this->tamil_book, ENT_NOQUOTES, "UTF-8")
        ];
    }
}