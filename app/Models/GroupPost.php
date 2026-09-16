<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'group_posts';
    protected $fillable = [
        'church_id', 'user_id', 'group_id', 'title', 'message', 'status', 'attachments', 'attachment_type',
    ];

     public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
