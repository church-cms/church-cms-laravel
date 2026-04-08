<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use SoftDeletes;

    protected $table = 'post_categories';

    protected $fillable = ['church_id', 'name', 'description', 'status'];

    protected $dates = ['deleted_at'];

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
