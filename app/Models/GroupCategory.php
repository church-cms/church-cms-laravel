<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GroupCategory Model
 *
 * Represents group classifications and categories.
 * Organizes groups into categories for better organization and filtering.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $category Category code or identifier
 * @property string|null $name Category display name
 * @property int $status Category status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $group Groups in this category
 */
class GroupCategory extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' , 'category' , 'name' , 'status'
    ];

    public function group()
    {
        return $this->hasMany('App\Models\Group','category_id','id');
    }
}
