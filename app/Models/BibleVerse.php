<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleVerse Model
 *
 * Represents individual Bible verses in the system.
 * Stores scripture content and references for ministry and devotional use.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $book_id Reference to Bible book
 * @property int|null $chapter Chapter number
 * @property int|null $verse_number Verse number
 * @property string|null $content The verse text content
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class BibleVerse extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bible_verses';
}
