<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PostDetail extends Model
{
    //
    use SoftDeletes;

    /**
     * PostDetail Model
     *
     * Tracks interactions (likes/unlikes) on posts.
     * Records user engagement with individual posts.
     *
     * @package App\Models
     * @property int $id Primary key
     * @property int|null $user_id Foreign key to user interacting
     * @property int|null $post_id Foreign key to post
     * @property bool $is_like Whether this is a like (true) or unlike (false)
     * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
     * @property \Carbon\Carbon $created_at Record creation timestamp
     * @property \Carbon\Carbon $updated_at Record update timestamp
     *
     * Relations:
     * @property-read \App\Models\User $user The user who liked/unliked
     * @property-read \App\Models\Post $post The post being interacted with
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_details';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id' , 'post_id' , 'like' , 'unlike' , 'save' , 'status'
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
    	return $this->belongsTo('\App\Models\Post','post_id');
    }

    public function scopeByLikeCount($query,$post_id)
    {
        $count = $query->where('post_id',$post_id)->where('like',1)->count();

        return $count;
    }

    public function scopeByUnlikeCount($query,$post_id)
    {
        $count = $query->where('post_id',$post_id)->where('unlike',1)->count();

        return $count;
    }
}
