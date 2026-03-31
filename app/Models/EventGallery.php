<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * EventGallery Model
 *
 * Represents photo galleries associated with church events.
 * Stores event-specific photo collections and media files.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $event_id Foreign key to event
 * @property string|null $path File path to gallery image
 * @property int|null $created_by User who created the gallery
 * @property int|null $updated_by User who last updated the gallery
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this gallery belongs to
 * @property-read \App\Models\Events $events The event this gallery is for
 */
class EventGallery extends Model
{
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_galleries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
    	'church_id' , 'event_id' , 'path' , 'created_by' , 'updated_by'
    ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function events()
    {
        return $this->belongsTo('App\Models\Events','event_id');
    }

    public function getFullPathAttribute()
    {
    	return $this->getFilePath($this->path);
    }
}
