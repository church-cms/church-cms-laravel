<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'church_id',
        'user_id',
        'amount',
        'currency',
        'category',
        'method',
        'gateway_ref',
        'status',
        'note',
        'uuid',
        'donated_at',
    ];

    protected $casts = [
        'donated_at' => 'datetime',
        'amount'     => 'decimal:2',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
