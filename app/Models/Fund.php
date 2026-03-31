<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Fund Model
 *
 * Represents church funds and donation tracking.
 * Manages financial records, fund types, and payment processing for donations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $payaccount_id Payment account used
 * @property int|null $authorised_by User who authorized the fund
 * @property \Carbon\Carbon|null $authorised_at Authorization timestamp
 * @property string|null $membership Membership type associated
 * @property int|null $user_id Donor user ID
 * @property array|null $data Additional data as JSON
 * @property decimal $amount Fund amount
 * @property string|null $method Payment method
 * @property array|null $payment_details Payment details as JSON
 * @property string $status Fund status (pending, approved, rejected, completed)
 * @property string|null $uuid Unique identifier for this fund
 * @property string|null $comments Administrative comments
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this fund belongs to
 * @property-read \App\Models\User $user The donor
 * @property-read \App\Models\User $admin The authorizing administrator
 * @property-read \App\Models\Payaccount $payaccount The payment account used
 */
class Fund extends Model
{
	//
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table='funds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'church_id' ,'payaccount_id', 'authorised_by' , 'authorised_at' , 'membership' , 'user_id' , 'data' , 'amount' , 'method' , 'payment_details' , 'status' , 'uuid' , 'comments'
	];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
	protected $casts = ['data'=>'array' , 'payment_details'=>'array'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

	public function admin()
    {
    	return $this->belongsTo('App\Models\User','authorised_by');
    }

    public function payaccount()
    {
        return $this->belongsTo('App\Models\Payaccount','payaccount_id');
    }
}
