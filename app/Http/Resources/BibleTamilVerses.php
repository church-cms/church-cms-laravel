<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BibleTamilVerses extends JsonResource
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
            'id'            =>  $this->id, 
            'tamil_verse'   =>  htmlspecialchars($this->tamil_verse, ENT_NOQUOTES, "UTF-8"),
            'book_id'       =>  $this->book_id, 
            'chapter_id'    =>  $this->chapter_id, 
            'verse_id'      =>  $this->verse_id, 
        ];
    }
}