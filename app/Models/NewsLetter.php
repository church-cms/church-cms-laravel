<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * NewsLetter Model
 *
 * Represents newsletter publications and distributions.
 * Manages church newsletters sent to members and subscribers.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $title Newsletter title
 * @property string|null $content Newsletter content
 * @property string|null $status Status (draft, published, archived)
 * @property \Carbon\Carbon|null $published_at Publication date
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this newsletter belongs to
 */
class NewsLetter extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_letters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'name' , 'email' , 'status'
    ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }
}
