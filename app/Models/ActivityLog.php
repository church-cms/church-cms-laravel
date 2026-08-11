<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ActivityLog Model
 *
 * Represents system activity logs for audit trail and user action tracking.
 * Logs all significant activities performed by users within the system.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string $log_name The name/category of the log entry
 * @property string $description Detailed description of the activity
 * @property string|null $subject Resource identifier that was affected
 * @property int|null $causer_id ID of the user who performed the action
 * @property array|null $properties JSON data about the activity
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $user The user who performed the activity
 */
class ActivityLog extends Model
{    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_name' , 'description' , 'subject' , 'causer' , 'properties'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=[
        'properties' => 'array'
    ];

    protected $with = array('user');

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'causer_id');
    }
}
