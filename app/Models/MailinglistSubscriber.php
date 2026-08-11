<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * MailinglistSubscriber Model
 *
 * Junction model for mailing list subscriptions.
 * Tracks subscriber memberships in mailing lists.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $subscribers_id Foreign key to subscriber
 * @property int|null $mailing_list_id Foreign key to mailing list
 * @property int $status Subscription status
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class MailinglistSubscriber extends Pivot
{
    //
    use SoftDeletes;

    /**
      * The table associated with the model.
      *
      * @var string
    */
    protected $table = 'mailing_list_subscribers';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
    protected $fillable = [
        'subscribers_id' , 'mailing_list_id' , 'status'
    ];

    protected $dates = ['deleted_at'];

    /*public function subscriber()
    {
        return $this->belongsTo('App\Models\Subscribers','subscriber_id','id');
    }*/
}
