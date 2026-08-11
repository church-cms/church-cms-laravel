<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * City Model
 *
 * Represents city/municipality geographic locations.
 * Used for organizing churches, users, and locations by city.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $country_id Foreign key to country
 * @property int|null $state_id Foreign key to state
 * @property string|null $name City name
 * @property int $status City status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Country $country The country this city belongs to
 * @property-read \App\Models\State $state The state this city belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $church Churches in this city
 * @property-read \Illuminate\Database\Eloquent\Collection $userprofile User profiles in this city
 */
class City extends Model
{    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id' , 'state_id' , 'name' , 'status'
    ];

    public function country()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    public function church()
    {
        return $this->belongsTo('App\Models\Church','city_id');
    }

    public function userprofile()
    {
        return $this->belongsTo('App\Models\Userprofile','city_id');
    }
}
