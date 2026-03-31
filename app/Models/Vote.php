<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Vote Model
 *
 * Represents votes and ratings on content.
 * Tracks voting activities for sermons, posts, and other voteable content.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to voting user
 * @property int|null $entity_id ID of the entity being voted on (polymorphic)
 * @property string|null $entity_name Type of entity (polymorphic)
 * @property bool $like Whether this is a like (true) or unlike (false)
 * @property bool|null $unlike Whether this is an unlike/dislike
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this vote is from
 * @property-read \App\Models\User $user The user who voted
 * @property-read \App\Models\Sermon $sermon The sermon being voted on (if applicable)
 */
class Vote extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'votes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'entity_id' , 'entity_name' , 'like' , 'unlike'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /*public function SermonlikeAttribute($church_id)
    {
        $query->where([[['church_id',$church_id],['entity_id',$entity_id],['entity_name','App\Models\Sermon']]]);
        return $query;
    }*/

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function sermon()
    {
        return $this->belongsTo('App\Models\Sermon','id','entity_id');
    }
}
