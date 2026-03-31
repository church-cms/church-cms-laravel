<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * PrayerResponse Model
 *
 * Represents updates and responses to prayer requests.
 * Tracks prayer answers and community feedback on prayer requests.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $prayer_request_id Foreign key to prayer request
 * @property int|null $user_id Foreign key to responder
 * @property string|null $response Response message
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this response belongs to
 * @property-read \App\Models\PrayerRequest $prayerRequest The prayer request being answered
 * @property-read \App\Models\User $user The member providing the response
 */
class PrayerResponse extends Model
{
    //
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prayer_responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'prayer_id' , 'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function prayerRequest()
    {
        return $this->belongsTo('App\Models\PrayerRequest','prayer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
