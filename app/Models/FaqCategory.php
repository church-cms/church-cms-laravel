<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * FaqCategory Model
 *
 * Represents categories for organizing FAQs.
 * Groups frequently asked questions by topic or category for better organization.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $name Category name
 * @property int $status Category status (active/inactive)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this category belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $faq FAQs in this category
 */
class FaqCategory extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faq_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'church_id' , 'name' , 'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function church()
    {
    	return $this->belongsTo('\App\Models\Church','church_id');
    }

    public function faq()
    {
    	return $this->hasMany('\App\Models\Faq','faq_category_id','id');
    }
}
