<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Reminder Model
 *
 * Represents event and prayer reminders.
 * Manages notifications for events, prayer requests, and scheduled reminders.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $entity_id ID of the entity being reminded about (polymorphic)
 * @property string|null $entity_name Type of entity (polymorphic)
 * @property int|null $user_id Foreign key to recipient user
 * @property string|null $reminder_type Type of reminder (email, sms, in-app)
 * @property \Carbon\Carbon|null $remind_at Time to send reminder
 * @property bool $is_sent Whether reminder has been sent
 * @property array|null $properties Additional data as JSON
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $events Associated events
 * @property-read \App\Models\Church $church The church this reminder is for
 * @property-read \App\Models\User $user The recipient of the reminder
 * @property-read \Illuminate\Database\Eloquent\Collection $userSms SMS reminders (if applicable)
 * @property-read \Illuminate\Database\Eloquent\Collection $prayerRequest Prayer request reminders
 */
class Reminder extends Model
{
    use SoftDeletes;

    /**
      * The table associated with the model.
      *
      * @var string
      */
    protected $table = 'reminder';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
     */
    protected $fillable = [
        'church_id' , 'from' , 'to' , 'subject' , 'message' , 'entity_id' , 'entity_name' , 'via' , 'queue_status' , 'sms_response' , 'executed_at' , 'template_id' , 'data'
    ];

    /**
      * The attributes that should be mutated to dates.
      *
      * @var array
      */
    protected $casts = ['data'=>'array' , 'sms_response'=>'array'];

    public function events()
    {
        return $this->belongsTo('App\Models\Events','entity_id');
    }

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','to','email');
    }

    public function userSms()
    {
        return $this->belongsTo('App\Models\User','to','mobile_no');
    }

    public function prayerRequest()
    {
        return $this->belongsTo('App\Models\PrayerRequest','entity_id');
    }
}
