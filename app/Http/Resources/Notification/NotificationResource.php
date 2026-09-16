<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = json_decode($this->data);

        if ($data->data->type != null) {
            $val = $data->data->data;
        } else {
            $val = $data->data;
        }

        return [
            
            'id'                =>  $this->id,
            'type'              =>  $this->type,
            'message_type'      =>  $data->message_type,
            'message_id'        =>  (int) $data->message_id,
            'notifiable_type'   =>  $this->notifiable_type,
            'notifiable_id'     =>  $this->notifiable_id,
            'data_message'      =>  $data->data,
            'web_message'       =>  $val,
            'read_at'           =>  $this->read_at == null ? '' : Carbon::parse($this->read_at)->diffForHumans(),
            'created_at'        =>  Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
