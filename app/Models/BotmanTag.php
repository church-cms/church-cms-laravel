<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BotmanTag Model
 *
 * Represents tags used in the Botman system.
 * Organizes and categorizes chatbot keywords and responses.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $master_id Foreign key to BotmanMaster
 * @property string|null $name Tag name
 * @property int|null $created_by User who created this tag
 * @property int|null $updated_by User who last updated this tag
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class BotmanTag extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'botman_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'master_id', 'name', 'created_by', 'updated_by'
    ];
}
