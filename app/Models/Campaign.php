<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Campaign Model
 *
 * Represents email marketing campaigns.
 * Manages campaign creation, email distribution, and tracking.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $mailinglist_id Mailing list for this campaign
 * @property string|null $name Campaign name
 * @property string|null $description Campaign description
 * @property string $status Campaign status (draft, sent, etc.)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $emails Emails in this campaign
 * @property-read \App\Models\Mailinglist $mailinglist The mailing list for this campaign
 * @property-read \Illuminate\Database\Eloquent\Collection $queue Campaign email queue
 * @property-read \Illuminate\Database\Eloquent\Collection $campaignEmail Campaign email assignments
 * @property-read \Illuminate\Database\Eloquent\Collection $rule Campaign rules/responses
 */
class Campaign extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
	protected $table = 'campaign';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
	protected $fillable = [
        'church_id' , 'name' , 'description' , 'status' , 'mailinglist_id'
	];

    public function emails()
    {
        return $this->belongsToMany('App\Models\Email')->using('App\Models\CampaignEmail')->withTimeStamps();
    }

    /*public function emails()
    {
        return $this->hasManyThrough('App\Email', 'App\Campignemail');
    }

    public function emails()
    {
        return $this->belongsToMany('App\Mailinglist');
    }*/

    public function mailinglist()
    {
        return $this->belongsTo('App\Models\Mailinglist', 'mailinglist_id', 'id');
    }

    public function queue()
    {
        return $this->hasMany('App\Models\MailQueue','campaign_id','id');
    }

    public function campaignEmail()
    {
        return $this->hasMany('App\Models\CampaignEmail','campaign_id','id');
    }

    public function rule()
    {
        return $this->hasMany('App\Models\GetResponse','campaign_id','id');
    }
}
