<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Contact Model
 *
 * Represents contact form submissions and inquiries.
 * Stores messages from website visitors and members with their contact details.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $fullname Full name of the person submitting contact
 * @property string|null $email Email address
 * @property string|null $mobile Mobile phone number
 * @property string|null $query Contact message or inquiry
 * @property \Carbon\Carbon|null $date_of_submission Date when submission was made
 * @property array|null $properties Additional metadata as JSON
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this contact is for
 */
class Contact extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'fullname' , 'email' , 'mobile' , 'query' , 'date_of_submission' , 'properties'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=[
        'properties' => 'array'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at' , 'date_of_submission'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }
}
