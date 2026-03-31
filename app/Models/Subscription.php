<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Subscription Model
 *
 * Represents church subscription to service plans.
 * Manages church subscriptions, billing, and service entitlements.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $church_id Foreign key to church
 * @property int|null $user_id Foreign key to subscriber (administrator)
 * @property int|null $plan_id Foreign key to plan
 * @property string $status Subscription status (active, inactive, canceled, expired)
 * @property \Carbon\Carbon|null $started_at Subscription start date
 * @property \Carbon\Carbon|null $ends_at Subscription end date
 * @property decimal|null $price Subscription price
 * @property string|null $renewal_type Renewal type (monthly, yearly, lifetime)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The subscribed church
 * @property-read \App\Models\User $user The subscription administrator
 * @property-read \App\Models\Plan $plan The subscription plan
 */
class Subscription extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'plan_id' ,'end_date', 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=['payment_details'=>'array' , 'card_details'=>'array' , 'plan_details'=>'array'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function plan()
    {
    	return $this->belongsTo('App\Models\Plan','plan_id');
    }

    public function scopeDate($query,$start,$end)
    {

        $query->whereDate('created_at','>=',$start)
              ->whereDate('created_at','<=',$end);

        return $query;
    }
}
