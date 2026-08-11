<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Webhook Model
 *
 * Represents webhook event subscriptions.
 * Manages event notifications sent to external services.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $mailing_list_id Foreign key to mailing list
 * @property string|null $event_type Type of event to trigger webhook
 * @property string|null $webhook_url URL to call on event
 * @property string|null $secret Secret for webhook signature verification
 * @property bool $is_active Whether webhook is active
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\MailingList $mailinglist The associated mailing list
 */
class Webhook extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='webhooks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'verb' , 'name' , 'url' , 'mailinglist_id' , 'handshake_key' , 'status'
    ];

    public function mailinglist()
    {
     	return $this->belongsTo('App\Models\Mailinglist', 'mailinglist_id', 'id');
    }

    public function scopeByChurch($query , $church_id)
    {
        $query->wherehas('mailinglist',function ($query) use($church_id)
        {
            $query->where('church_id',$church_id);
        });
        return $query;
    }
}
