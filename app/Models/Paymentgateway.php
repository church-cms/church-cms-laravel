<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

/**
 * Paymentgateway Model
 *
 * Represents payment processing gateway configurations.
 * Manages different payment processors (Stripe, PayPal, etc.).
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Gateway name (Stripe, PayPal, Square, etc.)
 * @property string|null $description Gateway description
 * @property string|null $configuration Gateway configuration as JSON
 * @property int $status Status (enabled/disabled)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Paymentgateway extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paymentgateways';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'gatewayname' , 'displayname' , 'status','instructions'
    ];
}
