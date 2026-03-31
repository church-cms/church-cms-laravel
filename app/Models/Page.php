<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Page Model
 *
 * Represents static and dynamic website pages.
 * Manages page content, metadata, and page organizational properties.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $page_category_id Foreign key to page category
 * @property string|null $title Page title
 * @property string|null $slug URL-friendly slug
 * @property string|null $description Page description/excerpt
 * @property string|null $content Page HTML content
 * @property string|null $cover_image Cover image path
 * @property int $status Page status (published/draft/archived)
 * @property int|null $created_by User who created the page
 * @property int|null $updated_by User who last updated the page
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this page belongs to
 * @property-read \App\Models\PageCategory $pageCategory The page category
 * @property-read \App\Models\User $user Creator user information
 * @property-read \Illuminate\Database\Eloquent\Collection $pageDetail Page details/interactions
 * @property-read \Illuminate\Database\Eloquent\Collection $pageAttachment Page attachments
 */
class Page extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'category_id' , 'page_name' , 'description' , 'cover_image' , 'created_by' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function church()
    {
    	return $this->belongsTo('\App\Models\Church','church_id');
    }

    public function pageCategory()
    {
        return $this->belongsTo('\App\Models\PageCategory','category_id');
    }

    public function user()
    {
    	return $this->belongsTo('\App\Models\User','created_by');
    }

    public function pageDetail()
    {
    	return $this->hasMany('\App\Models\PageDetail','page_id','id');
    }

    public function pageAttachment()
    {
    	return $this->hasMany('\App\Models\PageAttachment','page_id','id');
    }

    public function getCoverImagePathAttribute()
    {
        return $this->getFilePath($this->cover_image);
    }
}
