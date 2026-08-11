<?php

namespace App\Http\Resources;

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
        return [
            'church_id'  => $this->church_id,
            'user_id'    => $this->user_id,
            'sermons_id' => $this->sermons_id,
            'title'      => $this->title,
            'date'       => date('Y-m-d', strtotime($this->date)),
            'video_link' => $this->video_link,
            'audio_link' => $this->audio_link,
            'pdf_link'   => $this->pdf_link ? $this->getFilePath($this->pdf_link) : null,
        ];
    }
}
