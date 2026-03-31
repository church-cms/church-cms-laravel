<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;
use Carbon\Carbon;

/**
 * CampaignEmail Model
 *
 * Represents the junction between campaigns and emails with scheduling details.
 * Manages email assignments to campaigns with delay and timing configurations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $campaign_id Foreign key to campaign
 * @property int $email_id Foreign key to email
 * @property int|null $delay_in_days Days to delay before sending
 * @property int|null $delay_in_hours Hours to delay before sending
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Email $email The email being sent
 * @property-read \App\Models\Campaign $campaign The campaign this email belongs to
 */
class CampaignEmail extends Pivot
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
    */
    protected $table = 'campaign_email';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
    */
    protected $fillable = [
        'church_id' , 'campaign_id' , 'email_id' , 'delay_in_days' , 'delay_in_hours'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function email()
    {
        return $this->belongsTo('App\Models\Email','email_id');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign','campaign_id');
    }

    /*public function getdelayinhoursAttribute($value)
    {
        return $value/24;
    }

    public function setdelayinhoursAttribute($value)
    {
        $this->attributes['delay_in_hours'] = $value*24;
    }
    *///imp

    public function ScheduleAt()
    {
        $split_hour=Carbon::parse($this->delay_in_hours)->format('H');
        $split_minute=Carbon::parse($this->delay_in_hours)->format('i');
        $final=Carbon::today()->addDays($this->delay_in_days)->addHours($split_hour)->addMinutes($split_minute);

        return $final;
    }
}
