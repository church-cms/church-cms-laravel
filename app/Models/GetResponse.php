<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * GetResponse Model
 *
 * Integration with GetResponse email marketing service.
 * Manages GetResponse campaign configurations and email open tracking rules.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $campaign_id Foreign key to campaign
 * @property string|null $name Rule/configuration name
 * @property int|null $email_open_campaign_id Campaign ID for email opens
 * @property int|null $no_email_open_campaign_id Campaign ID for no opens
 * @property int|null $day_after Number of days to wait
 * @property string $status Rule status (active/inactive)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this rule belongs to
 * @property-read \App\Models\Campaign $campaign The associated campaign
 * @property-read \App\Models\Campaign $emailOpenCampaign Campaign for email opens
 * @property-read \App\Models\Campaign $noEmailOpenCampaign Campaign for no opens
 */
class GetResponse extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='get_response';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'campaign_id' , 'name' , 'email_open_campaign_id' , 'no_email_open_campaign_id' , 'day_after' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign','campaign_id');
    }

    public function emailOpenCampaign()
    {
        return $this->belongsTo('App\Models\Campaign','email_open_campaign_id');
    }

    public function noEmailOpenCampaign()
    {
        return $this->belongsTo('App\Models\Campaign','no_email_open_campaign_id');
    }
}
