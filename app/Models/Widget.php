<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Widget Model
 *
 * Represents configurable dashboard widgets.
 * Manages customizable widgets displayed on user dashboards.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Widget name/type
 * @property string|null $description Widget description
 * @property string|null $configuration Widget configuration as JSON
 * @property int $display_order Display order on dashboard
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $userInfo Associated user information
 */
class Widget extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'widgets';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' ,'slug' , 'content' , 'created_by' , 'updated_by'
    ];

    public function userInfo(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
}
