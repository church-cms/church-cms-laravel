<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrayerCategory extends Model
{
    use HasFactory;
    protected $table = 'prayer_categories';

    protected $fillable = [
        'church_id',
        'name',
        'css_class',
        'emoji',
        'display_color',
        'gradient_start',
        'gradient_end',
        'sort_order',
        'is_active',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    public function prayers()
    {
        return $this->hasMany(Prayer::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    // ===== QUERY SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    // ===== METHODS =====

    /**
     * Returns true if the category has no PENDING or ACTIVE prayers.
     */
    public function canBeDeleted()
    {
        return !$this->prayers()
            ->whereIn('status', [Prayer::STATUS_PENDING, Prayer::STATUS_ACTIVE])
            ->exists();
    }

    public function getDisplayNameAttribute()
    {
        return $this->emoji . ' ' . $this->name;
    }
}
