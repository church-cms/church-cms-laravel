<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Payaccount Model
 *
 * Represents payment gateway account configurations.
 * Stores payment processor credentials and settings.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $paymentgateway_id Foreign key to payment gateway
 * @property string|null $account_name Payment account name/label
 * @property string|null $account_details Account configuration details
 * @property int $status Account status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Paymentgateway $paymentgateway The payment gateway for this account
 */
class Payaccount extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payaccounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'paymentgateway_id' , 'status','attachment','comments','param1','param2','param3','param4','param5','param6','param7','param8'
    ];

    public function paymentgateway()
    {
        return $this->belongsTo('App\Models\Paymentgateway','paymentgateway_id');
    }

}
