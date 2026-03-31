<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * FeedbackMessage Model
 *
 * Represents individual messages within feedback/support threads.
 * Stores conversation messages between users and administrators.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to message author
 * @property int $feedback_id Foreign key to parent feedback
 * @property string|null $message Message content
 * @property array|null $file Attached files as JSON
 * @property string|null $category Message category/type
 * @property bool $is_seen Whether message has been read
 * @property bool $deleted_from_sender Deleted from sender's view
 * @property bool $deleted_from_receiver Deleted from receiver's view
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this message belongs to
 * @property-read \App\Models\User $user The user who sent the message
 * @property-read \App\Models\Feedback $feedback The parent feedback thread
 */
class FeedbackMessage extends Model
{
    //
    use PresentableTrait;
    use SoftDeletes;
    use Common;

    protected $presenter = "App\Presenters\UserPresenter";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback_messages';


    protected $appends = array('message');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'feedback_id' , 'message' , 'file' , 'category' , 'is_seen' , 'deleted_from_sender' , 'deleted_from_receiver'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=[ 'file' => 'array' ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function feedback()
    {
        return $this->belongsTo('App\Models\Feedback','feedback_id');
    }

    public function getMessageAttribute($message)
    {
        //return \Purify::clean($message);
        return $message;
    }

    public function getFilePathAttribute()
    {
        $count = count($this->file);
        for($i=0 ; $i < $count ; $i++)
        {
            $attachment[$i] = $this->getFilePath($this->file[$i]);
        }
        return $attachment;
    }
}
