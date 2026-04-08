<?php

namespace App\Models;

use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use App\Traits\Common;

/**
 * PageAttachment Model
 *
 * Represents files attached to pages.
 * Manages media and document attachments for page content.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $page_id Foreign key to page
 * @property string|null $attachment_name Attachment file name
 * @property string|null $attachment_path File path
 * @property string|null $mime_type File MIME type
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Page $page The page this attachment belongs to
 */
class PageAttachment extends Model implements HasMedia
{
    //
    use InteractsWithMedia;
    use SoftDeletes;
    use Common;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id' , 'attachment_file' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function page()
    {
    	return $this->belongsTo('\App\Models\Page','page_id');
    }

    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment_file);
    }
}
