<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * MailQueue Model
 *
 * Represents emails queued for sending.
 * Manages email dispatch queue with tracking and delivery status.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $email_id Foreign key to email template
 * @property int|null $subscriber_id Recipient subscriber
 * @property int|null $mailinglist_id Foreign key to mailing list
 * @property int|null $campaign_id Foreign key to campaign
 * @property string|null $subject Email subject
 * @property string|null $from_email Sender email
 * @property string|null $from_name Sender name
 * @property string|null $reply_to_email Reply-to address
 * @property string|null $to_mail Recipient email
 * @property string|null $content Email body content
 * @property \Carbon\Carbon|null $scheduled_at Scheduled send time
 * @property string|null $username Recipient username if available
 * @property bool $is_read Whether email was opened
 * @property int|null $clicks Number of link clicks
 * @property string $status Delivery status (queued, sent, failed, bounced)
 * @property \Carbon\Carbon|null $fired_at Actual send time
 * @property \Carbon\Carbon|null $failed_at Failure timestamp
 * @property \Carbon\Carbon|null $rule_checked_at Last rule check time
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Campaign $campaign The associated campaign
 * @property-read \App\Models\Subscribers $subscriber The recipient subscriber
 * @property-read \App\Models\Email $email The email template
 * @property-read \App\Models\MailingList $mailinglist The mailing list
 * @property-read \App\Models\GetResponse $response GetResponse tracking
 */
class MailQueue extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='mail_queues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
        'email_id','subscriber_id','mailinglist_id','campaign_id','subject','from_email','from_name','reply_to_email','to_mail','content','scheduled_at','username','is_read','clicks','status'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates =['deleted_at','scheduled_at','fired_at','failed_at','rule_checked_at'];

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign','campaign_id','id');
    }

    public function subscriber()
    {
        return $this->belongsTo('App\Models\Subscribers','subscriber_id','id');
    }

    public function email()
    {
        return $this->belongsTo('App\Models\Email','email_id','id');
    }

    public function mailinglist()
    {
        return $this->belongsTo('App\Models\MailingList','mailinglist_id','id');
    }

    public function response()
    {
        return $this->hasOne('App\Models\GetResponse','campaign_id','campaign_id');
    }

    public function scopeByChurch($query , $church_id)
    {
        $query->wherehas('campaign',function ($query) use($church_id)
        {
            $query->where('church_id',$church_id);
        });
        return $query;
    }
}
