<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Email Model
 *
 * Represents email message templates and configurations.
 * Stores email content, sender information, and campaign associations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $subject Email subject
 * @property string|null $from_email From email address
 * @property string|null $from_name From display name
 * @property string|null $reply_to_email Reply-to email address
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $campaign Associated campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection $queue Email queue items
 */
class Email extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
	protected $table = 'emails';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
	protected $fillable = [
        'church_id' , 'subject' , 'from_email','from_name','reply_to_email'
	];

    public function campaign()
    {
        return $this->belongsToMany('App\Models\Campaign');
    }

    public function queue()
    {
        return $this->hasMany('App\Models\MailQueue','email_id','id');
    }
}
