<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * ChurchDetail Model
 *
 * Stores metadata and configuration details for churches.
 * Manages key-value pair configurations like logos, banners, and custom settings.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string $meta_key Configuration key/option name
 * @property string|null $meta_value Configuration value/option value
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this configuration belongs to
 */
class ChurchDetail extends Model
{
	//
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='church_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
	    'church_id' , 'meta_key' , 'meta_value'
	];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function getLogoPathAttribute()
    {
        if($this->meta_key == 'church_logo')
        {
          return $this->getFilePath($this->meta_value);
        }
    }
}
