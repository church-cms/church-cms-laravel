<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Quote Model
 *
 * Represents inspirational and motivational quotes.
 * Stores quotes for daily inspiration and member encouragement.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to quote author/submitter
 * @property string|null $quote The quote text
 * @property string|null $author Author name
 * @property string|null $image Image associated with quote
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this quote belongs to
 * @property-read \App\Models\User $user The user who submitted the quote
 */
class Quote extends Model
{
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='quotes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
	    'church_id' , 'user_id' ,  'image' , 'text' , 'tamil_quotes' , 'english_quotes' , 'publish_on' , 'published_at' , 'status'
	];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['publish_on' , 'published_at'];

	public function getImagePathAttribute()
	{
		return $this->getFilePath($this->image);
	}

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
