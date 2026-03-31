<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * SermonLink Model
 *
 * Represents links to sermon media files (video, audio).
 * Manages sermon recordings and streaming links.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $sermons_id Foreign key to sermon
 * @property int|null $user_id Foreign key to uploader
 * @property string|null $link Sermon file URL or link
 * @property string|null $link_type Type of link (youtube, vimeo, audio, document)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $sermons Associated sermons
 * @property-read \App\Models\Church $church The church this sermon link belongs to
 * @property-read \App\Models\User $user The user who provided the link
 */
class SermonLink extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
      */
    protected $table = 'sermons_links';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'sermons_id' , 'type' , 'location' , 'date' , 'url'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function sermons()
    {
        return $this->belongsTo('App\Models\Sermon','sermons_id');
    }

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function getUrlPathAttribute()
    {
        return $this->getFilePath($this->url);
    }
}
