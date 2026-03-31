<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Favorite Model
 *
 * Tracks user favorites for various content types.
 * Allows members to save their favorite sermons, posts, and other resources.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to user
 * @property int|null $entity_id ID of the favorited entity (polymorphic)
 * @property string|null $entity_name Type of entity (polymorphic)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Sermon $sermon The sermon if this is a sermon favorite
 * @property-read \App\Models\Church $church The church this favorite belongs to
 */
class Favorite extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'entity_id' , 'entity_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function sermon()
    {
        return $this->belongsTo('App\Models\Sermon','id');
    }

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function scopeBySermonFavorite($query,$church_id,$user_id)
    {
        $query->where([['church_id',$church_id],['user_id',$user_id],['entity_name','App\Models\Sermon']]);
        return $query;
    }
}
