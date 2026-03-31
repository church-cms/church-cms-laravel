<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * VideoConferenceUser Model
 *
 * Represents user participation in video conferences.
 * Tracks conference participants and their join/leave times.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $video_conference_id Foreign key to conference
 * @property int|null $user_id Foreign key to participant
 * @property \Carbon\Carbon|null $joined_at Time participant joined
 * @property \Carbon\Carbon|null $left_at Time participant left
 * @property string|null $status Participation status (joined, left, absent)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $usersInfo Participant user information
 * @property-read \App\Models\VideoConference $videoConference The conference this user is in
 * @property-read \App\Models\User $user The participant
 */
class VideoConferenceUser extends Model
{
	//
	use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'video_conference_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conference_id' , 'participant_id'
    ];

    public function usersInfo()
    {
        return $this->hasOne('App\Models\User', 'id', 'participant_id');
    }

    public function videoConference()
    {
        return $this->belongsTo('App\Models\VideoConference','conference_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','participant_id');
    }
}
