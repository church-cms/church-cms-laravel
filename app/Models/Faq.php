<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Faq Model
 *
 * Represents frequently asked questions for the church.
 * Stores Q&A pairs organized by categories for member self-service.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int|null $faq_category_id Foreign key to FAQ category
 * @property string|null $question The question text
 * @property string|null $answer The answer/response text
 * @property int|null $order Display order of this FAQ
 * @property int $status FAQ status (published/draft)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this FAQ belongs to
 * @property-read \App\Models\FaqCategory $faqCategory The category of this FAQ
 */
class Faq extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faqs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'church_id' , 'faq_category_id' , 'question' , 'answer' , 'order' , 'status'
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

    public function faqCategory()
    {
    	return $this->belongsTo('\App\Models\FaqCategory','faq_category_id');
    }
}
