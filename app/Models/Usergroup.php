<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Usergroup Model
 *
 * Represents user group classifications and roles.
 * Defines user group types (member, admin, pastor, staff, etc.).
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name User group name/title
 * @property string|null $description User group description
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $user Users in this group
 */
class Usergroup extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'user_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' , 'name'
    ];

	public function user()
    {
        return $this->hasMany('App\Models\User','usergroup_id');
    }
}
