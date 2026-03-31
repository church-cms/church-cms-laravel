<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Attendance Model
 *
 * Represents attendance records for church events, services, and activities.
 * Tracks presence or absence of members at various church functions.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $user_id Foreign key to user
 * @property int $entity_id ID of the entity (event, service, etc.)
 * @property string $entity_name Name/type of entity for polymorphic relations
 * @property string $title Title of the attendance record
 * @property string|null $category Category of attendance
 * @property \Carbon\Carbon $date Date of the attendance
 * @property bool $is_present Whether the member was present
 * @property \Carbon\Carbon|null $present_at Timestamp when presence was marked
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this attendance belongs to
 * @property-read \App\Models\User $user The member's attendance record
 * @property-read \App\Models\Events $events The event this attendance is for
 */
class Attendance extends Model
{    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'entity_id' , 'entity_name' , 'title' , 'category' , 'date' , 'is_present' , 'present_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'date' , 'present_at' , 'deleted_at' ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
   	{
   		return $this->belongsTo('App\Models\User','user_id');
   	}

   	public function events()
   	{
   		return $this->belongsTo('App\Models\Events','entity_id');
   	}
}
