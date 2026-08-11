<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * State Model
 *
 * Represents states or provinces in the geographic hierarchy.
 * Used for organizing churches, users, and locations by state/province.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $country_id Foreign key to country
 * @property string|null $name State/province name
 * @property string|null $code State/province code
 * @property int $status State status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church Churches in this state
 * @property-read \Illuminate\Database\Eloquent\Collection $userprofile User profiles in this state
 * @property-read \App\Models\Country $country The country this state belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $city Cities in this state
 */
class State extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id' , 'name' , 'status'
    ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','state_id');
    }

    public function userprofile()
    {
        return $this->belongsTo('App\Models\Userprofile','state_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }

    public function city()
    {
        return $this->hasMany('App\Models\City','city_id','id');
    }
}
