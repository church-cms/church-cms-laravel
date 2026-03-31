<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Notes Model
 *
 * Represents notes and annotations attached to entities.
 * Stores event notes, meeting notes, and other annotations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $entity_id ID of the entity (polymorphic)
 * @property string|null $entity_name Type of entity (polymorphic)
 * @property string|null $note Content of the note
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User $user Author of the note
 * @property-read \App\Models\Events $events Associated event if applicable
 */
class Notes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes';

    public function user()
   	{
    	return $this->belongsTo('App\Models\User','id');
   	}

   	public function event()
   	{
    	return $this->belongsTo('App\Models\Events','id');
   	}
}
