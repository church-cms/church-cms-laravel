<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * BotmanMessage Model
 *
 * Represents messages within the Botman system.
 * Tracks individual bot messages and user interactions.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $user_id Foreign key to user
 * @property string|null $message The message content
 * @property string|null $reply Reply to the message
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $userInfo The user associated with this message
 */
class BotmanMessage extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'botman_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'message', 'reply'
    ];

    public function userInfo(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
