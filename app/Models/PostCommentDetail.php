<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PostCommentDetail extends Model
{
    //
    use SoftDeletes;

    /**
     * PostCommentDetail Model
     *
     * Tracks interactions (likes/unlikes) on post comments.
     * Records user engagement with individual comments.
     *
     * @package App\Models
     * @property int $id Primary key
     * @property int|null $user_id Foreign key to user interacting
     * @property int|null $post_comment_id Foreign key to comment
     * @property bool $is_like Whether this is a like (true) or unlike (false)
     * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
     * @property \Carbon\Carbon $created_at Record creation timestamp
     * @property \Carbon\Carbon $updated_at Record update timestamp
     *
     * Relations:
     * @property-read \App\Models\User $user The user who liked/unliked
     * @property-read \App\Models\PostComment $postComment The comment being interacted with
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_comment_details';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id' , 'post_comment_id' ,  'like' , 'unlike' , 'status'
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

    public function postComment()
    {
    	return $this->belongsTo('\App\Models\PostComment','post_comment_id');
    }
}
