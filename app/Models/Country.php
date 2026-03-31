<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Country Model
 *
 * Represents countries in the geographic hierarchy.
 * Foundation model for state and city relationships.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Country name
 * @property string|null $code ISO country code
 * @property int $status Country status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $userprofile User profiles in this country
 * @property-read \Illuminate\Database\Eloquent\Collection $state States in this country
 * @property-read \Illuminate\Database\Eloquent\Collection $city Cities in this country
 */
class Country extends Model
{    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'short_name' , 'iso_code' , 'tel_prefix' , 'status' , 'order'
    ];

    public function userprofile()
    {
        return $this->belongsTo('App\Models\Userprofile','country_id');
    }

    public function state()
    {
        return $this->hasMany('App\Models\State','state_id','id');
    }

    public function city()
    {
        return $this->hasMany('App\Models\City','city_id','id');
    }
}
