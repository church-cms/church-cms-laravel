<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Authentication Model
 *
 * Manages user authentication tokens and session information.
 * Tracks authentication tokens, IP addresses, and expiration for security.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $user_id Foreign key to user
 * @property string $token Authentication token
 * @property string $ip_address IP address of the authenticated session
 * @property \Carbon\Carbon $expires_on Token expiration timestamp
 * @property string $status Status of the authentication record
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $user The user associated with this authentication
 */
class Authentication extends Model
{    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authentications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id' , 'token' , 'ip_address' , 'expires_on' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at' , 'expires_on'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
