<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * PageDetail Model
 *
 * Tracks interactions and details on pages.
 * Records user engagements, likes, dislikes, and comments on pages.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $page_id Foreign key to page
 * @property int|null $user_id Foreign key to user
 * @property bool $is_like Whether this is a like (true) or unlike (false)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $user The user who interacted with the page
 * @property-read \App\Models\Page $page The page being interacted with
 */
class PageDetail extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_details';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id' , 'page_id' , 'is_following' , 'like' , 'dislike' , 'status'
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

    public function page()
    {
    	return $this->belongsTo('\App\Models\Page','page_id');
    }

    public function scopeByLike($query,$page_id)
    {
        $count = $query->where('page_id',$page_id)->where('like',1)->get();

        return $count;
    }

    public function scopeByUnlike($query,$page_id)
    {
        $count = $query->where('page_id',$page_id)->where('dislike',1)->count();

        return $count;
    }
}
