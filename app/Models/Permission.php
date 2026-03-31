<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

/**
 * Permission Model
 *
 * Represents system permissions for role-based access control.
 * Defines granular permissions that can be assigned to roles and users.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string $name Permission codename
 * @property string $display_name Human-readable permission name
 * @property string|null $description Permission description
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Permission extends LaratrustPermission
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'display_name' , 'description'
    ];

    public function permissionUser()
    {
        return $this->hasMany('App\Models\PermissionUser','permission_id');
    }
}
