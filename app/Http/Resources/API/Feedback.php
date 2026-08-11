<?php

namespace App\Http\Resources\API;

use App\Http\Resources\API\FeedbackMessage as FeedbackMessageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class Feedback extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $show = FeedbackMessageResource::collection($this->feedbackMessage);

        if($this->latestMessage->is_seen == '0')
        {
            $status = 'Message Not Yet Viewed';
        }
        elseif($this->latestMessage->is_seen == 'has_seen')
        {
            $status = 'Message Has Been Seen';
        }
        elseif($this->latestMessage->is_seen == 'action_taken')
        {
            $status = 'Action Has Been Taken';
        }
        
        return
        [
            'feedback_id'   => $this->id,
            'messages'      => $show,
            'category'      => ucwords(str_replace('_', ' ', (str_replace('/', ' / ',$this->latestMessage->category)))),
            'status'        => $status,
            'created_on'    => date('d-m-Y H:i:s',strtotime($this->created_at)),
            'last_reply_by' => $this->feedbackMessage->last()->user->FullName,
            'last_reply_on' => date('d-m-Y H:i:s',strtotime($this->feedbackMessage->last()->created_at)),
        ];
    }
}