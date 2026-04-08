<?php

namespace App\Models;

use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use App\Traits\Common;

class Post extends Model implements HasMedia
{
    //
    use InteractsWithMedia;
    use SoftDeletes;
    use Common;

    /**
     * Post Model
     *
     * Represents user-generated posts with media attachments and nested comments.
     * Manages post creation, interaction tracking, and content management.
     *
     * @package App\Models
     * @property int $id Primary key
     * @property int $church_id Foreign key to church
     * @property int|null $entity_id ID of the entity (polymorphic)
     * @property string|null $entity_name Type of entity (polymorphic)
     * @property string|null $title Post title
     * @property string|null $description Post content/body
     * @property array|null $attachment_file Attached files as JSON
     * @property \Carbon\Carbon|null $post_created_at Post creation date (can differ from DB created_at)
     * @property bool $is_posted Whether post is published
     * @property \Carbon\Carbon|null $posted_at Publication timestamp
     * @property int $status Post status (published/draft/archived)
     * @property int|null $created_by User who created the post
     * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
     * @property \Carbon\Carbon $created_at Record creation timestamp
     * @property \Carbon\Carbon $updated_at Record update timestamp
     *
     * Relations:
     * @property-read \App\Models\Church $church The church this post belongs to
     * @property-read \App\Models\User $createdBy Creator user information
     * @property-read \App\Models\PostDetail $postDetail Post interaction detail
     * @property-read \Illuminate\Database\Eloquent\Collection $postComment Comments on this post
     * @property-read \Illuminate\Database\Eloquent\Collection $postReplyComment Reply comments
     * @property-read \Illuminate\Database\Eloquent\Collection $tag Tags on this post
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'category_id' , 'entity_id' , 'entity_name' , 'title' , 'description' , 'attachment_file' , 'post_created_at' , 'is_posted' , 'posted_at' , 'status' , 'created_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachment_file' => 'array',
        'post_created_at' => 'datetime',
        'posted_at'       => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['post_created_at','posted_at','deleted_at'];

    public function church()
    {
        return $this->belongsTo('\App\Models\Church','church_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function createdBy()
    {
    	return $this->belongsTo('\App\Models\User','created_by');
    }

    public function postDetail()
    {
        return $this->hasOne('\App\Models\PostDetail','post_id','id');
    }

    public function postComment()
    {
        return $this->hasMany('\App\Models\PostComment','entity_id','id')->where('entity_name','App\Models\Post');
    }

    public function postReplyComment()
    {
        return $this->hasMany('\App\Models\PostComment','entity_id','id')->where('entity_name','App\Models\PostComment');
    }

    public function getAttachmentPathAttribute()
    {
        if (empty($this->attachment_file)) {
            return [];
        }
        $count = count($this->attachment_file);
        for($i=0 ; $i < $count ; $i++)
        {
            $attachment[$i]['original_path']    = $this->attachment_file[$i];
            $attachment[$i]['path']             = $this->getFilePath($this->attachment_file[$i]);
            $attachment[$i]['id']               = $i;
        }

        return $attachment ?? [];
    }

    public function getImagePathAttribute()
    {
        $count = count($this->attachment_file);
        for($i=0 ; $i < $count ; $i++)
        {
            $attachment[$i]['path']             = $this->getFilePath($this->attachment_file[$i]);
        }

        return $attachment;
    }

    public function getCoverPathAttribute()
    {
        return $this->getFilePath($this->attachment_file[0]);
    }

    public function getPostCommentsAttribute()
    {
        $i = 0;
        $array = [];
        foreach ($this->postComment as $postComment)
        {
            $array[$i]['comment_id']        = $postComment->id;
            $array[$i]['post_id']           = $postComment->entity_id;
            $array[$i]['user_id']           = $postComment->user_id;
            $array[$i]['comments']          = $postComment->comments;
            $array[$i]['attachment_file']   = $postComment->attachment_file=='' ? null:$this->getFilePath($postComment->attachment_file);
            $array[$i]['user_name']         = $postComment->user->name;
            $array[$i]['user_fullname']     = ucwords($postComment->user->FullName);
            $array[$i]['user_avatar']       = $postComment->user->userprofile->AvatarPath;
            $array[$i]['commented_at']      = $postComment->updated_at->diffForHumans();
            $array[$i]['comment_details']   = $postComment->PostCommentDetails;
            $array[$i]['like_count']        = $postComment->CommentLikeCount;
            $array[$i]['unlike_count']      = $postComment->CommentUnlikeCount;
            $postCommentDetail = PostCommentDetail::where('post_comment_id',$postComment->id)->first();
            if($postCommentDetail != null)
            {
                $array[$i]['like']              = $postCommentDetail->like;
                $array[$i]['unlike']            = $postCommentDetail->unlike;
            }
            $i++;
        }

        return $array;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }
}
