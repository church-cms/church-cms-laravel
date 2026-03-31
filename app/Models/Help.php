<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Help Model
 *
 * Represents help requests and support tickets from users.
 * Manages member assistance requests and support inquiries.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to user requesting help
 * @property string|null $title Help request title
 * @property string|null $description Detailed description of the help request
 * @property string|null $contact_details Contact information for follow-up
 * @property string $status Request status (open/resolved/closed)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this help request is for
 * @property-read \App\Models\User $user The user requesting help
 */
class Help extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'helps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'title' , 'description' , 'contact_details' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
