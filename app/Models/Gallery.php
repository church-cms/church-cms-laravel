<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;
use App\Models\Photos;

/**
 * Gallery Model
 *
 * Represents photo galleries for organizing collections of images.
 * Manages photo collections for events, galleries, and media organization.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $name Gallery name/title
 * @property string|null $description Gallery description
 * @property string|null $path Directory path for gallery images
 * @property int|null $created_by User who created the gallery
 * @property int|null $updated_by User who last updated the gallery
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $photos Photos in this gallery
 */
class Gallery extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
	protected $table = 'galleries';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
	protected $fillable = [
        'church_id' , 'name' , 'description' , 'path' , 'created_by' , 'updated_by'
	];

    public function photos()
    {
        return $this->hasMany('App\Models\Photos','gallery_id','id');
    }

    public function scopeByName($query , $name)
    {
        $query->where(function ($query) use($name)
        {
            $query->where('name','LIKE',$name.'%');
        });

        return $query;
    }

    public function getFullPathAttribute()
    {
        return $this->getFilePath($this->path);
    }

    public function getPhotoCount($gallery_id)
    {
        return Photos::where('gallery_id',$gallery_id)->count();
    }
}
