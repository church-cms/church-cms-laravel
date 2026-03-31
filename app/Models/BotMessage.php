<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * BotMessage Model
 *
 * Represents messages in the chatbot system.
 * Stores user messages and bot replies for conversation tracking.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $user_id Foreign key to user
 * @property string|null $message User's message to the bot
 * @property string|null $reply Bot's reply to the user
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class BotMessage extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'message', 'reply'
    ];
}
