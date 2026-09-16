<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowSermonLink extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->type == 'document') {
            $url = $this->UrlPath;
        } else {
            $url = $this->url;
        }
        return [
            'sermons_id'    =>  $this->sermons_id,
            'title'         =>  $this->sermons->title,
            'date'          =>  date('d-m-Y', strtotime($this->date)),
            'name'          =>  $this->user->name,
            'fullname'      =>  $this->user->FullName,
            'type'          =>  $this->type,
            'location'      =>  $this->location,
            // 'url'           =>  $this->UrlPath,
            'url'           =>  $url,
            'cover_image'   =>  $this->sermons->CoverImagePath,
            'sermon_date'   =>  date('M d Y', strtotime($this->date)),
            'video_link' => $this->video_link,
            'audio_link' => $this->audio_link,
            'pdf_link' => $this->PdfUrlPath ?? null,
        ];
    }
}
