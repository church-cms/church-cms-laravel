<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Webhook;
use App\Traits\Common;

/**
 * MailingList Model
 *
 * Represents email mailing lists for campaigns and newsletters.
 * Manages subscriber lists and email distribution groups.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $scope Scope of the mailing list (internal, public, etc.)
 * @property string|null $name Mailing list name
 * @property string|null $description List description
 * @property bool $is_published Whether list is published
 * @property string|null $slug URL-friendly identifier
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $subscribers All subscribers
 * @property-read \Illuminate\Database\Eloquent\Collection $activesubscribers Active subscribers
 * @property-read \Illuminate\Database\Eloquent\Collection $queue Emails in queue
 * @property-read \Illuminate\Database\Eloquent\Collection $webhooks Associated webhooks
 */
class MailingList extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
    protected $table = 'mailinglists';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
    protected $fillable = [
        'church_id' , 'scope' , 'name' , 'description' , 'is_published' , 'slug'
    ];

    public function subscribers()
    {
        return $this->belongsToMany('App\Models\Subscribers')->using('App\Models\MailinglistSubscriber')->withTimeStamps();
    }

    public function activesubscribers()
    {
        return $this->belongsToMany('App\Models\Subscribers')->where('is_active',1)->using('App\Models\MailinglistSubscriber')->withTimeStamps()->wherePivot('status', 1);
    }

    public function totalactivesubscribers($mailinglist_id)
    {
        //$total=MailinglistSubscriber::where('mailinglist_id',$mailinglist_id)->where('status','1')->count();
        $total=MailinglistSubscriber::where('mailing_list_id',$mailinglist_id)->count();

        return $total;
    }

    /*//Add csss
    public function campaign()
    {
        return $this->belongsToMany('App\Campaign','mailinglist_id');
    }*/

    public function queue()
    {
        return $this->hasMany('App\Models\MailQueue','mailing_list_id','id');
    }

    public function webhooks()
    {
        return $this->hasMany('App\Models\Webhook','mailing_list_id');
    }
}
