<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Feedback Model
 *
 * Represents user feedback and support tickets.
 * Manages conversations between members and administrators regarding issues or inquiries.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $user_id Foreign key to user submitting feedback
 * @property int|null $admin_id Foreign key to admin handling the feedback
 * @property string $status Feedback status (open, in-progress, resolved, closed)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $feedbackMessage Messages in this feedback thread
 * @property-read \App\Models\FeedbackMessage $latestMessage The most recent message
 * @property-read \App\Models\User $user The user who submitted the feedback
 * @property-read \App\Models\User $admin The admin handling the feedback
 */
class Feedback extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'admin_id' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function feedbackMessage()
    {
        return $this->hasMany('App\Models\FeedbackMessage','feedback_id','id');
    }

    public function latestMessage()
    {
        return $this->hasOne('App\Models\FeedbackMessage','feedback_id','id')->orderByDesc('id')->limit(1);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }
}
