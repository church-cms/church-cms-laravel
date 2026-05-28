<?php

namespace App\Http\Resources\API;

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
        /* if($this->type == 'video')
        {
            $url = $this->url;
        }

        else
        {
            $url = $this->UrlPath;
        }*/
        if ($this->type == 'document') {
            $url = $this->UrlPath;
        } else {
            $url = $this->url;
        }

        return [
            'sermons_id'    =>  $this->sermons_id,
            'title'         =>  $this->sermons->title,
            'total_likes'   =>  $this->sermons->sermonlikevote,
            'total_unlikes' =>  $this->sermons->sermonunlikevote,
            'like'          =>  $this->sermons->likevote,
            'unlike'        =>  $this->sermons->unlikevote,
            'type'          =>  $this->type,
            'location'      =>  $this->location,
            'url'           =>  $url,
            'cover_image'   =>  $this->sermons->CoverImagePath,
            'video_link' => $this->video_link,
            'audio_link' => $this->audio_link,
            'pdf_link' => $this->PdfUrlPath,
        ];
    }
}
