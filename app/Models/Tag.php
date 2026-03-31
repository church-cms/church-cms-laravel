<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostTag;
use App\Traits\Common;
use App\Models\Post;
use App\Models\Tag;

/**
 * Tag Model
 *
 * Represents content tags/categories for organizing posts and content.\n * Manages the relationship between tags and posts via the PostTag pivot table.\n * Provides tag count methods for querying tag usage frequency.\n *\n * @package App\\Models\n * @property int $id Primary key\n * @property string $tag_name Tag name/label\n * @property \\Carbon\\Carbon $created_at Record creation timestamp\n * @property \\Carbon\\Carbon $updated_at Record update timestamp\n *\n * Relations:\n * @property-read \\Illuminate\\Database\\Eloquent\\Collection $posts Posts tagged with this tag (many-to-many)\n * @property-read \\Illuminate\\Database\\Eloquent\\Collection $postTag PostTag pivot records for this tag\n */\nclass Tag extends Model
{
    //
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_name'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function postTag()
    {
        return $this->belongsToMany(PostTag::class);
    }

    public function getTag($tag_name)
    {
        $tag=Tag::where('tag_name',$tag_name)->first();

        $count=PostTag::where('tag_id',$tag->id)->count();

        return $count;
    }
}
