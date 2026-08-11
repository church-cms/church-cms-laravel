<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendee extends Model
{
    protected $table = 'event_attendees';

    protected $fillable = [
        'session_id', 'church_id', 'event_id',
        'user_id', 'scanned_at', 'scanned_by',
    ];

    protected $dates = ['scanned_at'];

    public function session()
    {
        return $this->belongsTo(EventAttendanceSession::class, 'session_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function userprofile()
    {
        return $this->belongsTo(Userprofile::class, 'user_id', 'user_id');
    }
}
