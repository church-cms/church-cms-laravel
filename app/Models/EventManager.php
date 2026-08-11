<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventManager extends Model
{
    protected $table = 'event_managers';

    protected $fillable = ['event_id', 'user_id'];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
