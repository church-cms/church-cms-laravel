<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVersion extends Model
{
    protected $table = 'page_versions';

    protected $fillable = [
        'page_id', 'version_number', 'content', 'description', 'layout_template', 'saved_by',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function savedBy()
    {
        return $this->belongsTo(User::class, 'saved_by');
    }
}
