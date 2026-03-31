<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Group Model
 *
 * Represents groups, ministries, and communities within the church.
 * Manages group memberships, categories, and group-specific information.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $category_id Foreign key to group category
 * @property string|null $name Group name
 * @property string|null $cover_image Group cover image
 * @property string|null $description Group description
 * @property string|null $group_type Type of group (ministry, community, class, etc.)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\GroupCategory $groupCategory The category this group belongs to
 * @property-read \App\Models\Church $church The church this group belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $groupLink Group member links
 */
class Group extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'category_id' , 'name', 'cover_image', 'description' , 'group_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function groupCategory()
    {
    	return $this->belongsTo('App\Models\GroupCategory','category_id');
    }

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function groupLink()
    {
        return $this->hasMany('App\Models\GroupLink','group_id','id');
    }

    public function getCoverImagePathAttribute()
    {
        return $this->getFilePath($this->cover_image);
    }
}
