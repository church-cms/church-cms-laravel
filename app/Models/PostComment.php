<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

class PostComment extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * PostComment Model
     *
     * Represents comments on posts with support for nested replies.
     * Manages post comments, interactions, and nested comment threads.
     *
     * @package App\Models
     * @property int $id Primary key
     * @property int|null $user_id Foreign key to commenter
     * @property int|null $post_id Foreign key to post if direct comment
     * @property int|null $entity_id ID for polymorphic comment references
     * @property string|null $entity_name Type of entity for polymorphic relation
     * @property string|null $comment Comment text content
     * @property array|null $attachment_file Attached files as JSON
     * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
     * @property \Carbon\Carbon $created_at Record creation timestamp
     * @property \Carbon\Carbon $updated_at Record update timestamp
     *
     * Relations:
     * @property-read \App\Models\User $user The comment author
     * @property-read \App\Models\Post $post The post being commented on
     * @property-read \Illuminate\Database\Eloquent\Collection $postComment Nested replies to this comment
     * @property-read \Illuminate\Database\Eloquent\Collection $postCommentDetail Comment interactions (likes)
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id' , 'entity_id' , 'entity_name' , 'comments' , 'attachment_file' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
    	return $this->belongsTo('\App\Models\User','user_id');
    }

    public function post()
    {
        return $this->belongsTo('\App\Models\Post','entity_id');
    }

    public function postComment()
    {
        return $this->belongsTo('\App\Models\PostComment','entity_id');
    }

    public function postCommentDetail()
    {
        return $this->hasMany('\App\Models\PostCommentDetail','post_comment_id','id');
    }

    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment_file);
    }

    public function getPostCommentDetailsAttribute()
    {
        $i = 0;
        $array = [];
        foreach ($this->postCommentDetail as $postCommentDetail)
        {
            $array[$i]['detail_id']         = $postCommentDetail->id;
            $array[$i]['user_id']           = $postCommentDetail->user_id;
            $array[$i]['user_name']         = $postCommentDetail->user->name;
            $array[$i]['user_fullname']     = ucwords($postCommentDetail->user->FullName);
            $array[$i]['user_avatar']       = $postCommentDetail->user->userprofile->AvatarPath;
            $i++;
        }

        return $array;
    }

    public function getCommentLikeCountAttribute()
    {
        if($this->postCommentDetail != null)
        {
            return $this->postCommentDetail->where('like',1)->count();
        }
        return 0;
    }

    public function getCommentUnlikeCountAttribute()
    {
        if($this->postCommentDetail != null)
        {
            return $this->postCommentDetail->where('unlike',1)->count();
        }
        return 0;
    }
}
