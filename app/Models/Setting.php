<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Setting Model
 *
 * Stores global system configuration settings.
 * Manages application-wide preferences and configuration values.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string $key Setting key/option name
 * @property string|null $value Setting value
 * @property string|null $description Setting description
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Setting extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'settings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [
        'key' , 'name' , 'description' , 'value' , 'field' , 'active'
    ];
}
