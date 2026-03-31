<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * MediaFile Model
 *
 * Manages media files and documents uploaded to the system.
 * Tracks file uploads, storage paths, and file-related metadata.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $file_name Name of the file
 * @property string|null $file_path File storage path
 * @property string|null $mime_type MIME type of file
 * @property int|null $file_size File size in bytes
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church owning this file
 */
class MediaFile extends Model
{
	//
    use SoftDeletes;
	use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='media_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
	    'church_id' , 'media_type' , 'name' , 'description' , 'type' , 'url' , 'created_by' , 'updated_by'
	];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

	public function getUrlPathAttribute()
	{
		return $this->getFilePath($this->url);
	}
}
