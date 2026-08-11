<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendanceSession extends Model
{
    protected $table = 'event_attendance_sessions';

    protected $fillable = [
        'church_id', 'event_id', 'attendance_date',
        'opened_by', 'locked_at', 'locked_by',
    ];

    protected $dates = ['attendance_date', 'locked_at'];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendee::class, 'session_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereNull('locked_at');
    }
}
