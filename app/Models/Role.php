<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

/**
 * Role Model
 *
 * Represents user roles for role-based access control.
 * Extends Laravel-Trusts's base role model with Church CMS customizations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string $name Role name/codename
 * @property string $display_name Human-readable role name
 * @property string|null $description Role description
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Role extends LaratrustRole
{
    //
}
