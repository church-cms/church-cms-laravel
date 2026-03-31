<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * PrayerRequest Model
 *
 * Represents prayer requests submitted by members.
 * Manages prayer intentions and community prayer coordination.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to request submitter
 * @property string|null $title Prayer request title
 * @property string|null $description Detailed prayer request
 * @property string|null $audio Audio file path for voice requests
 * @property string|null $image Image attachment path
 * @property bool $is_anonymous Whether request is anonymous
 * @property string $status Request status (open, answered, archived)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this request is posted to
 * @property-read \App\Models\User $user The member who requested prayer
 * @property-read \Illuminate\Database\Eloquent\Collection $prayerResponse Responses/updates to this request
 */
class PrayerRequest extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prayer_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'title' , 'description' , 'image' , 'audio' , 'date' , 'status' , 'comments'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function prayerResponse()
    {
        return $this->hasMany('App\Models\PrayerResponse','prayer_id','id');
    }

    public function getAudioPathAttribute()
    {
        return $this->getFilePath($this->audio);
    }

    public function getImagePathAttribute()
    {
        return $this->getFilePath($this->image);
    }
}
