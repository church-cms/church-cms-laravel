<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Plan Model
 *
 * Represents subscription plan tiers.
 * Defines available subscription levels and pricing for churches.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Plan name
 * @property string|null $description Plan description
 * @property decimal $price Monthly price in currency
 * @property int|null $max_users Maximum users allowed
 * @property int|null $max_files Maximum files allowed
 * @property int $status Plan status (active/inactive)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $subscription Subscriptions to this plan
 */
class Plan extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cycle' , 'name' , 'order' , 'active' , 'amount' , 'no_of_members' , 'no_of_events' , 'no_of_folders' , 'no_of_files' , 'no_of_bulletins'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates=['created_at' , 'updated_at' , 'deleted_at'];

    public function subscription()
    {
        return $this->hasMany('App\Models\Subscription','id','plan_id');
    }
}
