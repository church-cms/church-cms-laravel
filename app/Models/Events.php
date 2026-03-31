<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Common;

/**
 * Events Model
 *
 * Represents church events and activities.
 * Manages event details, scheduling, recurring events, and event-related operations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $select_type Event type classification
 * @property string|null $title Event title
 * @property string|null $description Event description
 * @property string|null $repeats Whether event repeats (yes/no)
 * @property string|null $freq Recurrence frequency (daily, weekly, monthly, yearly)
 * @property string|null $freq_term Recurrence term/pattern
 * @property string|null $location Event location
 * @property string|null $category Event category
 * @property string|null $organised_by Person/group organizing the event
 * @property string|null $image Event image/cover photo
 * @property \Carbon\Carbon|null $start_date Event start date and time
 * @property \Carbon\Carbon|null $end_date Event end date and time
 * @property bool $allDay Whether event spans full day
 * @property int|null $created_by User who created the event
 * @property int|null $updated_by User who last updated the event
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this event belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $notes Notes associated with this event
 * @property-read \Illuminate\Database\Eloquent\Collection $eventreminder Reminders for this event
 */
class Events extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'select_type' , 'title' , 'description' , 'repeats' , 'freq' , 'freq_term' , 'location' , 'category' , 'organised_by' , 'image' , 'start_date' , 'end_date' , 'allDay' , 'created_by' , 'updated_by'
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

    public function notes()
    {
        return $this->hasMany('App\Models\Notes','entity_id','id');
    }

    public function scopeByChurch($query,$church_id)
    {
        $query->where('church_id',$church_id);
        return $query;
    }

    public function eventreminder()
    {
        return $this->hasMany('App\Models\Reminder', 'entity_id','id')->where('entity_name','=','App\\Models\\Events');
    }

    public function getImagePathAttribute()
    {
        if($this->image==null)
        {
            return $this->eventImagePath($this->category,$this->image);
        }
    }
}
