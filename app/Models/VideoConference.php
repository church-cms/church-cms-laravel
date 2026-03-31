<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * VideoConference Model
 *
 * Represents video conference meetings and sessions.
 * Manages virtual meeting configurations and participant tracking.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $name Conference name/title
 * @property string|null $description Conference description
 * @property string|null $conference_code Unique conference identifier
 * @property \Carbon\Carbon|null $start_time Conference start time
 * @property \Carbon\Carbon|null $end_time Conference end time
 * @property string $status Conference status (scheduled, ongoing, ended)
 * @property int|null $created_by User who created the conference
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church hosting this conference
 * @property-read \Illuminate\Database\Eloquent\Collection $participantInfo Participant information
 * @property-read \Illuminate\Database\Eloquent\Collection $userInfo Associated users
 * @property-read \Illuminate\Database\Eloquent\Collection $videoConferenceUser Participant details
 */
class VideoConference extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'video_conferences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' ,'user_id' , 'name' , 'description' , 'slug' , 'room_id' , 'compose_id' , 'compose_status' , 'status' , 'created_by' , 'updated_by'
    ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function participantInfo()
    {
        return $this->belongsToMany('App\Models\User', 'video_conference_users', 'conference_id', 'participant_id')->whereNull('video_conference_users.deleted_at');
    }

    public function userInfo()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function videoConferenceUser()
    {
        return $this->hasMany('App\Models\VideoConferenceUser','conference_id','id');
    }
}
