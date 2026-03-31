<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Photos Model
 *
 * Represents individual photos within galleries.
 * Stores photo files and metadata for gallery collections.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $gallery_id Foreign key to gallery
 * @property string|null $photo_name Photo file name
 * @property string|null $path Photo file path
 * @property string|null $description Photo description/caption
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Gallery $gallery The gallery containing this photo
 */
class Photos extends Model
{
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
    protected $table='photos';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
    protected $fillable=[
        'church_id' , 'gallery_id' , 'path' , 'created_by' , 'updated_by'
    ];

    protected $appends = ['FullPath'];

  	public function gallery()
  	{
    	return $this->belongsTo('App\Models\Gallery','gallery_id');
    }

    public function getFullPathAttribute()
    {
    	return $this->getFilePath($this->path);
    }
}
