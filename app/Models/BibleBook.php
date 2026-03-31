<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBook Model
 *
 * Represents Bible book references in the system.
 * Contains book information for scripture management and references.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Bible book name
 * @property int|null $chapter_count Number of chapters in the book
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class BibleBook extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bible_books';
}
