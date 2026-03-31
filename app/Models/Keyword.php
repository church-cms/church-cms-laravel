<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Keyword Model
 *
 * Represents keywords for chatbot trigger recognition.
 * Stores keywords that trigger automated chatbot responses.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Keyword text
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Keyword extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
