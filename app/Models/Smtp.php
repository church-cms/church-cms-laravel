<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Smtp Model
 *
 * Stores SMTP email server configurations.
 * Manages email service provider settings for outgoing mail.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $church_id Foreign key to church (if church-specific)
 * @property string|null $host SMTP server hostname
 * @property int|null $port SMTP server port
 * @property string|null $username SMTP authentication username
 * @property string|null $password SMTP authentication password
 * @property string|null $encryption Encryption type (TLS, SSL, null)
 * @property string|null $from_email Default from email address
 * @property string|null $from_name Default from display name
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Smtp extends Model
{
    //
    use SoftDeletes;

    /**
      * The table associated with the model.
      *
      * @var string
    */
    protected $table = 'smtp';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
    protected $fillable = [
    	'host' , 'port' , 'username' , 'password' , 'encryption' , 'status'
    ];
}
