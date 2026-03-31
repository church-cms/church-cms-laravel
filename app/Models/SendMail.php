<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * SendMail Model
 *
 * Tracks emails sent through the system.
 * Records all outgoing email communications for audit and tracking purposes.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to sender
 * @property int|null $group_id Foreign key to recipient group
 * @property string|null $to_email Recipient email address
 * @property string|null $subject Email subject
 * @property string|null $body Email body/content
 * @property array|null $attachment_file Attachments as JSON
 * @property string $status Send status (sent, failed, bounced)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this email was sent from
 * @property-read \App\Models\User $user The sender
 * @property-read \App\Models\Group $group The recipient group (if applicable)
 */
class SendMail extends Model
{
    //
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'send_mail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'entity_id' , 'entity_name' , 'from_address' , 'mode' , 'from' , 'to' , 'subject' , 'message' , 'attachments' , 'status' , 'type' , 'message_id' , 'read_at' , 'executed_at' , 'is_executed' , 'fired_at' , 'read_status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['executed_at' , 'fired_at' , 'read_at' , 'deleted_at'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachments);
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group','entity_id');
    }
}
