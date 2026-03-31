<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PermissionUser Model
 *
 * Assigns permissions directly to users.
 * Tracks user-specific permission grants independent of roles.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $user_id Foreign key to user
 * @property int|null $permission_id Foreign key to permission
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Permission $permission The permission granted
 * @property-read \App\Models\User $user The user with this permission
 */
class PermissionUser extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id' , 'user_id' , 'user_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission','permission_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
