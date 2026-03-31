<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * PostTag Model
 *
 * Associates tags with posts for content categorization.
 * Represents the many-to-many relationship between posts and tags.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $post_id Foreign key to post
 * @property int|null $tag_id Foreign key to tag
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Tag $tag The tag assigned to the post
 */
class PostTag extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_tag';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id','post_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public function tag()
    {
        return $this->hasMany(Tag::class);
    }
}
