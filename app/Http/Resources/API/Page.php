<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\PageDetail;

class Page extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            //
            'id'            =>  $this->id,
            'page_name'     =>  $this->page_name,
            'description'   =>  strip_tags($this->description),
            'cover_image'   =>  $this->CoverImagePath,
            'category'      =>  str_replace('_', ' ', ucwords($this->pageCategory->name)),
            'like_count'    =>  $this->pageDetail()->where('like',1)->count(),
            'unlike_count'  =>  $this->pageDetail()->where('dislike',1)->count(),
            'follow_count'  =>  $this->pageDetail()->where('is_following',1)->count(),
        ];
        $pagedetail = PageDetail::where([['user_id',Auth::id()],['page_id',$this->id]])->first();
        if($pagedetail != null)
        {
            $array['is_following']  =  $pagedetail->is_following;
            $array['like']          =  $pagedetail->like;
            $array['dislike']       =  $pagedetail->dislike;
        }

        return $array;
    }
}