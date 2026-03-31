<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Bulletin Model
 *
 * Represents church bulletins and newsletter publications.
 * Manages weekly or monthly bulletins with cover images and attachments.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $name Bulletin name or title
 * @property string|null $cover_image Cover image file path
 * @property string|null $type Bulletin type (weekly, monthly, etc.)
 * @property int|null $week Week number for weekly bulletins
 * @property int|null $month Month number for monthly bulletins
 * @property int|null $year Year for the bulletin
 * @property string|null $path File path to bulletin document
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this bulletin belongs to
 */
class Bulletin extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bulletins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'name' , 'cover_image' , 'type' , 'week' , 'month' , 'year' , 'path'
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

    public function getFilePathAttribute()
    {
        return $this->getFilePath($this->path);
    }

    public function getCoverImagePathAttribute()
    {
        return $this->getFilePath($this->cover_image);
    }
}
