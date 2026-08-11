# Prayer Board - Laravel Models & Relationships

**Version:** 1.0  
**Framework:** Laravel 11+  
**Status:** Production Ready

---

## Table of Contents

1. [Models Overview](#models-overview)
2. [Model 1: PrayerCategory](#model-1-prayercategory)
3. [Model 2: Prayer](#model-2-prayer)
4. [Model 3: PrayerParticipant](#model-3-prayerparticipant)
5. [Relationships Summary](#relationships-summary)
6. [Query Scopes](#query-scopes)
7. [Factories & Seeders](#factories--seeders)
8. [Usage Examples](#usage-examples)

---

## Models Overview

```
PrayerCategory (1) ──────→ (M) Prayer (1) ──────→ (M) PrayerParticipant
                                    ↓                          ↓
                                  (1) User                   (1) User
```

---

## Model 1: PrayerCategory

**File:** `app/Models/PrayerCategory.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrayerCategory extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get all prayers in this category
     */
    public function prayers()
    {
        return $this->hasMany(Prayer::class, 'category_id');
    }

    /**
     * Get the user who created this category
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this category
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== QUERY SCOPES =====

    /**
     * Scope: Get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get categories ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope: Get active and ordered
     */
    public function scopeActiveAndOrdered($query)
    {
        return $query->active()->ordered();
    }

    /**
     * Scope: Get inactive categories
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Get categories by name (search)
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope: Get categories with active prayer count
     */
    public function scopeWithActivePrayerCount($query)
    {
        return $query->withCount([
            'prayers' => function ($q) {
                $q->active();
            }
        ])->as('active_prayer_count');
    }

    // ===== ACCESSORS & MUTATORS =====

    /**
     * Get category display name with emoji
     */
    public function getDisplayNameAttribute()
    {
        return $this->emoji . ' ' . $this->name;
    }

    // ===== METHODS =====

    /**
     * Check if category can be deleted
     * Cannot delete if has active or pending prayers
     */
    public function canBeDeleted()
    {
        return !$this->prayers()
            ->whereIn('status', ['PENDING', 'ACTIVE'])
            ->exists();
    }

    /**
     * Soft delete by deactivating
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Get category settings from GlobalSettings
     */
    public function getSettings()
    {
        $globalSettings = $this->getGlobalSettings();
        $categoryOverrides = $this->getCategoryOverrides();

        return array_merge($globalSettings, $categoryOverrides);
    }

    private function getGlobalSettings()
    {
        return [
            'allow_anonymous_submission' => setting('prayer:allowAnonymousSubmission', false),
            'allow_anonymous_participation' => setting('prayer:allowAnonymousParticipation', true),
            'default_expiry_days' => setting('prayer:defaultExpiryDays', 60),
            'min_expiry_days' => setting('prayer:minExpiryDays', 1),
            'max_expiry_days' => setting('prayer:maxExpiryDays', 90),
            'auto_delete_rejected_after_days' => setting('prayer:autoDeleteRejectedAfterDays', 7),
        ];
    }

    private function getCategoryOverrides()
    {
        $overrides = [];
        $categoryId = str_replace('-', '_', $this->id);

        $allowAnonSubmission = setting("prayer:categories:{$categoryId}:allowAnonymousSubmission");
        if ($allowAnonSubmission !== null) {
            $overrides['allow_anonymous_submission'] = $allowAnonSubmission;
        }

        $allowAnonParticipation = setting("prayer:categories:{$categoryId}:allowAnonymousParticipation");
        if ($allowAnonParticipation !== null) {
            $overrides['allow_anonymous_participation'] = $allowAnonParticipation;
        }

        $defaultExpiry = setting("prayer:categories:{$categoryId}:defaultExpiryDays");
        if ($defaultExpiry !== null) {
            $overrides['default_expiry_days'] = $defaultExpiry;
        }

        $minExpiry = setting("prayer:categories:{$categoryId}:minExpiryDays");
        if ($minExpiry !== null) {
            $overrides['min_expiry_days'] = $minExpiry;
        }

        $maxExpiry = setting("prayer:categories:{$categoryId}:maxExpiryDays");
        if ($maxExpiry !== null) {
            $overrides['max_expiry_days'] = $maxExpiry;
        }

        return $overrides;
    }
}
```

**Migration File:** `database/migrations/xxxx_xx_xx_create_prayer_categories_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Core fields
            $table->string('name', 50)->unique();
            $table->string('css_class', 50)->unique();
            $table->string('emoji', 4)->unique();
            
            // Colors
            $table->string('display_color', 7); // Hex color
            $table->string('gradient_start', 7); // Hex color
            $table->string('gradient_end', 7); // Hex color
            
            // Organization
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Description
            $table->string('description', 500)->nullable();
            
            // Audit
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('sort_order');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_categories');
    }
};
```

---

## Model 2: Prayer

**File:** `app/Models/Prayer.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Prayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'text',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
        'expiry_days',
        'expires_at',
        'should_delete_at',
        'pinned_at',
        'pinned_by',
        'answer_testimony',
        'answered_by',
        'answered_at',
        'participant_count',
    ];

    protected $casts = [
        'status' => 'string',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expires_at' => 'datetime',
        'should_delete_at' => 'datetime',
        'pinned_at' => 'datetime',
        'answered_at' => 'datetime',
        'expiry_days' => 'integer',
        'participant_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_ANSWERED = 'ANSWERED';
    const STATUS_ENDED = 'ENDED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_UNPUBLISHED = 'UNPUBLISHED';

    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_ACTIVE,
        self::STATUS_ANSWERED,
        self::STATUS_ENDED,
        self::STATUS_REJECTED,
        self::STATUS_UNPUBLISHED,
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the category this prayer belongs to
     */
    public function category()
    {
        return $this->belongsTo(PrayerCategory::class, 'category_id');
    }

    /**
     * Get the user who submitted this prayer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this prayer
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who rejected this prayer
     */
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the admin who pinned this prayer
     */
    public function pinner()
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }

    /**
     * Get the user who marked this prayer as answered
     */
    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    /**
     * Get all participants who prayed for this prayer
     */
    public function participants()
    {
        return $this->hasMany(PrayerParticipant::class, 'prayer_id');
    }

    /**
     * Get member participants
     */
    public function memberParticipants()
    {
        return $this->participants()->where('participant_type', 'MEMBER');
    }

    /**
     * Get guest participants
     */
    public function guestParticipants()
    {
        return $this->participants()->where('participant_type', 'GUEST');
    }

    /**
     * Get anonymous participants
     */
    public function anonymousParticipants()
    {
        return $this->participants()->where('participant_type', 'ANONYMOUS');
    }

    // ===== QUERY SCOPES =====

    /**
     * Scope: Get only active prayers
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope: Get only pending prayers
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Get only answered prayers
     */
    public function scopeAnswered($query)
    {
        return $query->where('status', self::STATUS_ANSWERED);
    }

    /**
     * Scope: Get only ended/expired prayers
     */
    public function scopeEnded($query)
    {
        return $query->where('status', self::STATUS_ENDED);
    }

    /**
     * Scope: Get only rejected prayers
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope: Get only unpublished prayers
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status', self::STATUS_UNPUBLISHED);
    }

    /**
     * Scope: Get prayers by category
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Get prayers by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get prayers expiring soon (within X days)
     */
    public function scopeExpiringWithin($query, $days = 7)
    {
        $date = now()->addDays($days);
        return $query->active()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $date);
    }

    /**
     * Scope: Get prayers that should be expired
     */
    public function scopeShouldExpire($query)
    {
        return $query->active()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope: Get prayers that should be deleted (rejected TTL passed)
     */
    public function scopeShouldDelete($query)
    {
        return $query->rejected()
            ->whereNotNull('should_delete_at')
            ->where('should_delete_at', '<=', now());
    }

    /**
     * Scope: Get pinned prayers first
     */
    public function scopeOrderByPinned($query)
    {
        return $query->orderByDesc('pinned_at')
            ->orderByDesc('created_at');
    }

    /**
     * Scope: Get recent prayers first
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Scope: Get prayers for public board (active, with participation counts)
     */
    public function scopeForPublicBoard($query)
    {
        return $query->active()
            ->with(['category', 'user:id,name'])
            ->withCount(['participants', 'memberParticipants', 'guestParticipants', 'anonymousParticipants'])
            ->orderByPinned();
    }

    /**
     * Scope: Get prayers for admin pending review
     */
    public function scopeForAdminPending($query)
    {
        return $query->pending()
            ->with(['category', 'user:id,email,name'])
            ->recent();
    }

    /**
     * Scope: Get prayers for admin active list
     */
    public function scopeForAdminActive($query)
    {
        return $query->active()
            ->with(['category', 'user:id,name', 'approver:id,name'])
            ->withCount(['participants', 'memberParticipants', 'guestParticipants', 'anonymousParticipants'])
            ->orderByPinned();
    }

    /**
     * Scope: Get prayers from specific month
     */
    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
    }

    /**
     * Scope: Get prayers from current month
     */
    public function scopeThisMonth($query)
    {
        return $query->inMonth(now()->year, now()->month);
    }

    // ===== ACCESSORS & MUTATORS =====

    /**
     * Get days remaining until expiry
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        $remaining = $this->expires_at->diffInDays(now(), false);
        return max(0, $remaining);
    }

    /**
     * Get expiry percentage (for progress bar)
     */
    public function getExpiryPercentageAttribute()
    {
        if (!$this->expires_at || !$this->created_at) {
            return 100;
        }

        $total = $this->created_at->diffInDays($this->expires_at);
        if ($total === 0) {
            return 100;
        }

        $elapsed = $this->created_at->diffInDays(now());
        $percentage = ($elapsed / $total) * 100;

        return min(100, max(0, $percentage));
    }

    /**
     * Get total participant count (calculated from breakdown)
     */
    public function getTotalParticipants()
    {
        return $this->member_count + $this->guest_count + $this->anonymous_count;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_ANSWERED => 'green',
            self::STATUS_ENDED => 'gray',
            self::STATUS_REJECTED => 'red',
            self::STATUS_UNPUBLISHED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get submitter name (handle null user_id)
     */
    public function getSubmitterNameAttribute()
    {
        return $this->user?->name ?? 'Anonymous';
    }

    // ===== METHODS =====

    /**
     * Submit a new prayer request
     */
    public static function createFromRequest(User $user, array $data)
    {
        $prayer = static::create([
            'category_id' => $data['category_id'],
            'user_id' => $user->id,
            'text' => $data['text'],
            'status' => self::STATUS_PENDING,
        ]);

        // Log to audit
        auditLog('prayer', $prayer->id, 'CREATED', $user->id, [
            'category_id' => $data['category_id'],
            'text' => substr($data['text'], 0, 50) . '...',
        ]);

        return $prayer;
    }

    /**
     * Approve prayer for publication
     */
    public function approve(User $admin, $expiryDays)
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'expiry_days' => $expiryDays,
            'expires_at' => now()->addDays($expiryDays),
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'APPROVED', $admin->id, [
            'status' => 'PENDING → ACTIVE',
            'expiry_days' => $expiryDays,
            'expires_at' => $this->expires_at,
        ]);

        return $this;
    }

    /**
     * Reject prayer request
     */
    public function reject(User $admin, $reason)
    {
        $categorySettings = $this->category->getSettings();

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
            'should_delete_at' => now()->addDays($categorySettings['auto_delete_rejected_after_days']),
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'REJECTED', $admin->id, [
            'status' => 'PENDING → REJECTED',
            'rejection_reason' => $reason,
        ]);

        return $this;
    }

    /**
     * Mark prayer as answered
     */
    public function markAnswered(User $user, $testimony = null)
    {
        $this->update([
            'status' => self::STATUS_ANSWERED,
            'answered_by' => $user->id,
            'answered_at' => now(),
            'answer_testimony' => $testimony,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'ANSWERED', $user->id, [
            'status' => 'ACTIVE → ANSWERED',
            'has_testimony' => !empty($testimony),
        ]);

        return $this;
    }

    /**
     * Extend prayer expiry
     */
    public function extend(User $admin, $additionalDays)
    {
        $oldExpiresAt = $this->expires_at;
        $newExpiresAt = $this->expires_at->addDays($additionalDays);

        $this->update([
            'expiry_days' => $this->expiry_days + $additionalDays,
            'expires_at' => $newExpiresAt,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'EXTENDED', $admin->id, [
            'old_expires_at' => $oldExpiresAt,
            'new_expires_at' => $newExpiresAt,
            'additional_days' => $additionalDays,
        ]);

        return $this;
    }

    /**
     * Pin prayer to top
     */
    public function pin(User $admin)
    {
        $this->update([
            'pinned_at' => now(),
            'pinned_by' => $admin->id,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'PINNED', $admin->id, [
            'pinned_at' => $this->pinned_at,
        ]);

        return $this;
    }

    /**
     * Unpin prayer
     */
    public function unpin(User $admin)
    {
        $this->update([
            'pinned_at' => null,
            'pinned_by' => null,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'UNPINNED', $admin->id, [
            'pinned_at' => null,
        ]);

        return $this;
    }

    /**
     * Unpublish prayer (remove from board)
     */
    public function unpublish(User $admin)
    {
        $this->update([
            'status' => self::STATUS_UNPUBLISHED,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'UNPUBLISHED', $admin->id, [
            'status' => 'ACTIVE → UNPUBLISHED',
        ]);

        return $this;
    }

    /**
     * Auto-expire prayer (nightly job)
     */
    public function expire()
    {
        $this->update([
            'status' => self::STATUS_ENDED,
        ]);

        // Log to audit
        auditLog('prayer', $this->id, 'EXPIRED', 'system', [
            'status' => 'ACTIVE → ENDED',
            'participant_count' => $this->getTotalParticipants(),
        ]);

        return $this;
    }

    /**
     * Permanently delete prayer (nightly job)
     */
    public function permanentlyDelete()
    {
        $prayerId = $this->id;

        // Delete participants
        $this->participants()->delete();

        // Delete prayer
        $this->delete();

        // Log to audit
        auditLog('prayer', $prayerId, 'DELETED', 'system', [
            'status' => 'REJECTED',
            'deleted_after_days' => 7,
        ]);

        return true;
    }

    /**
     * Increment participant count for specific type
     */
    public function incrementParticipantCount($type)
    {
        match($type) {
            'MEMBER' => $this->increment('member_count'),
            'GUEST' => $this->increment('guest_count'),
            'ANONYMOUS' => $this->increment('anonymous_count'),
        };
    }

    /**
     * Decrement participant count for specific type
     */
    public function decrementParticipantCount($type)
    {
        match($type) {
            'MEMBER' => $this->decrement('member_count'),
            'GUEST' => $this->decrement('guest_count'),
            'ANONYMOUS' => $this->decrement('anonymous_count'),
        };
    }

    /**
     * Check if user can participate
     */
    public function canUserParticipate(User $user)
    {
        // Must be active
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        // Check category permissions
        $categorySettings = $this->category->getSettings();
        if ($user->is_anonymous && !$categorySettings['allow_anonymous_participation']) {
            return false;
        }

        // Check if already participated
        if ($this->participants()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }
}
```

**Migration File:** `database/migrations/xxxx_xx_xx_create_prayers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Foreign keys
            $table->uuid('category_id');
            $table->uuid('user_id')->nullable(); // Can be null if user deleted
            
            // Prayer content
            $table->longText('original_text');
            $table->longText('text');
            
            // Status
            $table->enum('status', ['PENDING', 'ACTIVE', 'ANSWERED', 'ENDED', 'REJECTED', 'UNPUBLISHED'])->default('PENDING');
            
            // Approval workflow
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Rejection workflow
            $table->string('rejection_reason', 500)->nullable();
            $table->uuid('rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Expiry
            $table->integer('expiry_days')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('should_delete_at')->nullable();
            
            // Pin/Feature
            $table->timestamp('pinned_at')->nullable();
            $table->uuid('pinned_by')->nullable();
            
            // Answer workflow
            $table->longText('answer_testimony')->nullable();
            $table->uuid('answered_by')->nullable();
            $table->timestamp('answered_at')->nullable();
            
            // Denormalization - Only 3 fields (total is derived)
            $table->integer('member_count')->default(0);
            $table->integer('guest_count')->default(0);
            $table->integer('anonymous_count')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key constraints - CASCADE STRATEGY
            $table->foreign('category_id')
                ->references('id')
                ->on('prayer_categories')
                ->restrictOnDelete(); // ✅ Cannot delete if prayers exist

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->setNullOnDelete(); // ✅ Keep prayer if user deleted (orphaned)

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // ✅ Keep record if admin deleted

            $table->foreign('rejected_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // ✅ Keep record if admin deleted

            $table->foreign('pinned_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // ✅ Keep record if admin deleted

            $table->foreign('answered_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // ✅ Keep record if admin deleted
            
            // Indexes
            $table->index('status');
            $table->index('category_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('expires_at');
            $table->index(['status', 'expires_at']);
            $table->index(['status', 'category_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayers');
    }
};
```

**Cascade Delete Strategy:**

| Foreign Key | Strategy | Behavior |
|------------|----------|----------|
| `category_id` | **RESTRICT** | Cannot delete category if prayers exist |
| `user_id` | **SET NULL** | Keep prayer if user deleted (orphaned) |
| `approved_by` | **SET NULL** | Keep approval record if admin deleted |
| `rejected_by` | **SET NULL** | Keep rejection record if admin deleted |
| `pinned_by` | **SET NULL** | Keep pin record if admin deleted |
| `answered_by` | **SET NULL** | Keep answer record if admin deleted |

---

## Model 3: PrayerParticipant

**File:** `app/Models/PrayerParticipant.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrayerParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'prayer_id',
        'user_id',
        'participant_type',
        'ip_address',
        'session_id',
        'device_type',
        'is_first_time',
    ];

    protected $casts = [
        'is_first_time' => 'boolean',
        'prayed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const UPDATED_AT = null; // Don't update timestamp

    // Participant type constants
    const TYPE_MEMBER = 'MEMBER';
    const TYPE_GUEST = 'GUEST';
    const TYPE_ANONYMOUS = 'ANONYMOUS';

    public static $types = [
        self::TYPE_MEMBER,
        self::TYPE_GUEST,
        self::TYPE_ANONYMOUS,
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the prayer this participation belongs to
     */
    public function prayer()
    {
        return $this->belongsTo(Prayer::class, 'prayer_id');
    }

    /**
     * Get the user who participated
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== QUERY SCOPES =====

    /**
     * Scope: Get only member participants
     */
    public function scopeMembers($query)
    {
        return $query->where('participant_type', self::TYPE_MEMBER);
    }

    /**
     * Scope: Get only guest participants
     */
    public function scopeGuests($query)
    {
        return $query->where('participant_type', self::TYPE_GUEST);
    }

    /**
     * Scope: Get only anonymous participants
     */
    public function scopeAnonymous($query)
    {
        return $query->where('participant_type', self::TYPE_ANONYMOUS);
    }

    /**
     * Scope: Get participants for specific prayer
     */
    public function scopeForPrayer($query, $prayerId)
    {
        return $query->where('prayer_id', $prayerId);
    }

    /**
     * Scope: Get participant breakdown for prayer
     */
    public function scopeBreakdown($query, $prayerId)
    {
        return $query->forPrayer($prayerId)
            ->selectRaw('participant_type, COUNT(*) as count')
            ->groupBy('participant_type');
    }

    /**
     * Scope: Get user participation history
     */
    public function scopeUserHistory($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->with('prayer.category')
            ->orderByDesc('created_at');
    }

    /**
     * Scope: Get recent participants
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // ===== METHODS =====

    /**
     * Create a new participation record
     */
    public static function recordParticipation(Prayer $prayer, User $user)
    {
        // Check if already participated
        if (self::forPrayer($prayer->id)->where('user_id', $user->id)->exists()) {
            throw new \Exception('User already prayed for this prayer');
        }

        $participantType = $user->user_type ?? self::TYPE_GUEST;

        $participant = self::create([
            'prayer_id' => $prayer->id,
            'user_id' => $user->id,
            'participant_type' => $participantType,
            'session_id' => session()->getId(),
            'device_type' => $user->device_type,
            'is_first_time' => !self::userHasParticipated($user->id),
        ]);

        // Increment appropriate breakdown count
        $prayer->incrementParticipantCount($participantType);

        return $participant;
    }

    /**
     * Check if user has ever participated
     */
    public static function userHasParticipated($userId)
    {
        return self::where('user_id', $userId)->exists();
    }

    /**
     * Get participant count breakdown for prayer (calculated from denormalized fields)
     */
    public static function getBreakdownForPrayer($prayerId)
    {
        $prayer = Prayer::findOrFail($prayerId);
        
        return [
            'total' => $prayer->getTotalParticipants(),
            'members' => $prayer->member_count,
            'guests' => $prayer->guest_count,
            'anonymous' => $prayer->anonymous_count,
        ];
    }
}
```

**Migration File:** `database/migrations/xxxx_xx_xx_create_prayer_participants_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Foreign keys
            $table->uuid('prayer_id');
            $table->uuid('user_id')->nullable();
            
            // Participant info
            $table->enum('participant_type', ['MEMBER', 'GUEST', 'ANONYMOUS']);
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->boolean('is_first_time')->default(true);
            
            // Only creation timestamp (immutable record)
            $table->timestamp('created_at')->useCurrent();
            
            // Foreign keys
            $table->foreign('prayer_id')->references('id')->on('prayers')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->setNullOnDelete();
            
            // Indexes
            $table->unique(['prayer_id', 'user_id']);
            $table->index('prayer_id');
            $table->index('user_id');
            $table->index('participant_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_participants');
    }
};
```

---

## Relationships Summary

### One-to-Many Relationships

```php
// PrayerCategory has many Prayers
$category = PrayerCategory::find($id);
$prayers = $category->prayers()->active()->get();

// Prayer has many PrayerParticipants
$prayer = Prayer::find($id);
$participants = $prayer->participants()->recent()->get();

// User has many Prayers
$user = User::find($id);
$prayers = $user->prayers()->byUser($user->id)->get();

// User has many PrayerParticipants
$participationHistory = $user->prayerParticipants()->userHistory($user->id)->get();
```

### Belongs-To Relationships

```php
// Prayer belongs to PrayerCategory
$prayer = Prayer::find($id);
$category = $prayer->category;

// Prayer belongs to User (submitter)
$prayer = Prayer::find($id);
$submitter = $prayer->user;

// Prayer belongs to User (approver)
$approver = $prayer->approver;

// PrayerParticipant belongs to Prayer
$participant = PrayerParticipant::find($id);
$prayer = $participant->prayer;

// PrayerParticipant belongs to User
$user = $participant->user;
```

---

## Query Scopes

### PrayerCategory Scopes

```php
// Get active categories
$categories = PrayerCategory::active()->get();

// Get active and ordered
$categories = PrayerCategory::activeAndOrdered()->get();

// Get inactive categories
$inactive = PrayerCategory::inactive()->get();

// Search by name
$results = PrayerCategory::searchByName('health')->get();

// With active prayer count
$categories = PrayerCategory::withActivePrayerCount()->get();
foreach ($categories as $cat) {
    echo $cat->active_prayer_count;
}
```

### Prayer Scopes

```php
// Get active prayers
$active = Prayer::active()->get();

// Get pending prayers
$pending = Prayer::pending()->get();

// Get by category
$prayers = Prayer::inCategory($categoryId)->get();

// Get by user
$prayers = Prayer::byUser($userId)->get();

// Get expiring soon
$expiring = Prayer::expiringWithin(7)->get();

// Get prayers that should be expired
$shouldExpire = Prayer::shouldExpire()->get();

// Get prayers ready for deletion
$shouldDelete = Prayer::shouldDelete()->get();

// Get for public board
$public = Prayer::forPublicBoard()->paginate(20);

// Get for admin pending
$pending = Prayer::forAdminPending()
    ->inMonth(2026, 4)
    ->paginate(10);

// Get this month
$thisMonth = Prayer::thisMonth()->get();

// Order by pinned
$pinned = Prayer::orderByPinned()->get();
```

### PrayerParticipant Scopes

```php
// Get member participants
$members = PrayerParticipant::members()->get();

// Get guest participants
$guests = PrayerParticipant::guests()->get();

// Get anonymous participants
$anonymous = PrayerParticipant::anonymous()->get();

// Get for prayer
$participants = PrayerParticipant::forPrayer($prayerId)->get();

// Get breakdown
$breakdown = PrayerParticipant::breakdown($prayerId)->get();

// Get user history
$history = PrayerParticipant::userHistory($userId)->paginate(20);

// Get recent
$recent = PrayerParticipant::recent()->limit(10)->get();
```

---

## Usage Examples

### Getting Prayer Participant Counts (Denormalized)

```php
// Get total participants (calculated from breakdown)
$total = $prayer->getTotalParticipants(); // member_count + guest_count + anonymous_count

// Or access breakdown directly
$breakdown = [
    'members' => $prayer->member_count,
    'guests' => $prayer->guest_count,
    'anonymous' => $prayer->anonymous_count,
];

// Get breakdown static method
$breakdown = PrayerParticipant::getBreakdownForPrayer($prayerId);
// Returns: ['total' => 123, 'members' => 56, 'guests' => 42, 'anonymous' => 25]
```

### Handling Null user_id (User Deleted)

```php
// In views - use optional chaining
@if($prayer->user)
    <p>Submitted by: {{ $prayer->user->name }}</p>
@else
    <p>Submitted anonymously (user account deleted)</p>
@endif

// Using accessor
<p>Submitted by: {{ $prayer->submitter_name }}</p> <!-- Returns "Anonymous" if user deleted -->

// In API response
return [
    'prayer' => $prayer,
    'submitted_by' => $prayer->user?->name ?? 'Anonymous',
];
```

### Recording Participation

```php
// Lift a prayer (participate)
$user = auth()->user();
$prayer = Prayer::find($id);

// Validates permissions and prevents duplicates
PrayerParticipant::recordParticipation($prayer, $user);

// Automatically increments the appropriate count
// If user is MEMBER → member_count++
// If user is GUEST → guest_count++
// If user is ANONYMOUS → anonymous_count++
```

### Admin Actions

```php
// Approve prayer with expiry
$prayer->approve(auth()->user(), 60); // 60 days

// Reject prayer
$prayer->reject(auth()->user(), 'Does not meet community guidelines');

// Mark as answered
$prayer->markAnswered(auth()->user(), 'Praise God!');

// Extend expiry
$prayer->extend(auth()->user(), 30); // Add 30 more days

// Pin to top
$prayer->pin(auth()->user());

// Unpublish (remove from board)
$prayer->unpublish(auth()->user());
```

### Deleting Categories

```php
// Check if category can be deleted
if ($category->canBeDeleted()) {
    $category->delete();
} else {
    // Better: deactivate instead of delete
    $category->deactivate();
}

// Controller endpoint
public function destroy(PrayerCategory $category)
{
    if (!$category->canBeDeleted()) {
        return response()->json([
            'error' => 'Cannot delete - has active prayers',
            'active_count' => $category->prayers()->active()->count(),
        ], 422);
    }

    $category->delete();
    return response()->json(['success' => true]);
}
```

### Nightly Maintenance Jobs

```php
// app/Console/Commands/PrayerBoardMaintenance.php

class PrayerBoardMaintenance extends Command
{
    public function handle()
    {
        // Expire active prayers
        Prayer::shouldExpire()->each->expire();

        // Delete rejected prayers (after 7 days)
        Prayer::shouldDelete()->each->permanentlyDelete();

        // Sync counts (safety net)
        $this->syncCounts();
    }

    private function syncCounts()
    {
        Prayer::all()->each(function ($prayer) {
            $breakdown = DB::table('prayer_participants')
                ->where('prayer_id', $prayer->id)
                ->selectRaw('participant_type, COUNT(*) as count')
                ->groupBy('participant_type')
                ->pluck('count', 'participant_type');

            $prayer->update([
                'member_count' => $breakdown->get('MEMBER', 0),
                'guest_count' => $breakdown->get('GUEST', 0),
                'anonymous_count' => $breakdown->get('ANONYMOUS', 0),
            ]);
        });
    }
}
```

---

## Factories & Seeders

### Factory: PrayerCategoryFactory

**File:** `database/factories/PrayerCategoryFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\PrayerCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerCategoryFactory extends Factory
{
    protected $model = PrayerCategory::class;

    public function definition()
    {
        $names = ['Health', 'Family', 'Employment', 'Financial', 'Personal', 'Other'];
        $emojis = ['🏥', '👨‍👩‍👧‍👦', '💼', '💰', '✨', '❤️'];
        $colors = [
            ['start' => '#FEE2E2', 'end' => '#FCE7F3'],
            ['start' => '#FCE7F3', 'end' => '#EDE9FE'],
            ['start' => '#DBEAFE', 'end' => '#CFFAFE'],
            ['start' => '#FEF3C7', 'end' => '#FED7AA'],
            ['start' => '#EDE9FE', 'end' => '#E0E7FF'],
            ['start' => '#CCFBF1', 'end' => '#D1FAE5'],
        ];

        $index = array_rand($names);
        $name = $names[$index];
        $emoji = $emojis[$index];
        $color = $colors[$index];

        return [
            'name' => $name,
            'css_class' => 'category-' . str_slug($name),
            'emoji' => $emoji,
            'display_color' => $color['start'],
            'gradient_start' => $color['start'],
            'gradient_end' => $color['end'],
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
            'description' => fake()->sentence(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
```

### Factory: PrayerFactory

**File:** `database/factories/PrayerFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Prayer;
use App\Models\PrayerCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerFactory extends Factory
{
    protected $model = Prayer::class;

    public function definition()
    {
        return [
            'category_id' => PrayerCategory::factory(),
            'user_id' => User::factory(),
            'text' => fake()->paragraph(5),
            'status' => Prayer::STATUS_PENDING,
            'expiry_days' => 60,
            'participant_count' => 0,
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Prayer::STATUS_ACTIVE,
                'approved_by' => User::factory(),
                'approved_at' => now()->subDay(),
                'expires_at' => now()->addMonths(2),
                'participant_count' => fake()->numberBetween(10, 100),
            ];
        });
    }

    public function answered()
    {
        return $this->active()->state(function (array $attributes) {
            return [
                'status' => Prayer::STATUS_ANSWERED,
                'answered_by' => User::factory(),
                'answered_at' => now()->subDays(5),
                'answer_testimony' => fake()->paragraph(),
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Prayer::STATUS_REJECTED,
                'rejected_by' => User::factory(),
                'rejected_at' => now()->subDay(),
                'rejection_reason' => 'Does not meet community guidelines',
                'should_delete_at' => now()->addDays(6),
            ];
        });
    }

    public function pinned()
    {
        return $this->active()->state(function (array $attributes) {
            return [
                'pinned_at' => now(),
                'pinned_by' => User::factory(),
            ];
        });
    }
}
```

### Seeder: PrayerBoardSeeder

**File:** `database/seeders/PrayerBoardSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\PrayerCategory;
use App\Models\Prayer;
use App\Models\PrayerParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrayerBoardSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            [
                'name' => 'Health',
                'emoji' => '🏥',
                'css_class' => 'category-health',
                'display_color' => '#EF4444',
                'gradient_start' => '#FEE2E2',
                'gradient_end' => '#FCE7F3',
                'sort_order' => 1,
            ],
            [
                'name' => 'Family',
                'emoji' => '👨‍👩‍👧‍👦',
                'css_class' => 'category-family',
                'display_color' => '#EC4899',
                'gradient_start' => '#FCE7F3',
                'gradient_end' => '#EDE9FE',
                'sort_order' => 2,
            ],
            [
                'name' => 'Employment',
                'emoji' => '💼',
                'css_class' => 'category-employment',
                'display_color' => '#3B82F6',
                'gradient_start' => '#DBEAFE',
                'gradient_end' => '#CFFAFE',
                'sort_order' => 3,
            ],
            [
                'name' => 'Financial',
                'emoji' => '💰',
                'css_class' => 'category-financial',
                'display_color' => '#FBBF24',
                'gradient_start' => '#FEF3C7',
                'gradient_end' => '#FED7AA',
                'sort_order' => 4,
            ],
            [
                'name' => 'Personal',
                'emoji' => '✨',
                'css_class' => 'category-personal',
                'display_color' => '#A855F7',
                'gradient_start' => '#EDE9FE',
                'gradient_end' => '#E0E7FF',
                'sort_order' => 5,
            ],
        ];

        $admin = User::firstOrCreate(
            ['email' => 'admin@church.com'],
            ['name' => 'Admin User', 'user_type' => 'ADMIN_HEAD']
        );

        foreach ($categories as $category) {
            PrayerCategory::create(array_merge($category, [
                'is_active' => true,
                'description' => 'Prayer requests for ' . $category['name'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]));
        }

        // Create sample prayers
        $users = User::where('user_type', '!=', 'ADMIN_HEAD')->take(10)->get();
        if ($users->count() === 0) {
            $users = User::factory(10)->create(['user_type' => 'GUEST']);
        }

        $categories = PrayerCategory::all();

        foreach ($users as $user) {
            // Create active prayers
            Prayer::factory(2)
                ->active()
                ->create([
                    'category_id' => $categories->random()->id,
                    'user_id' => $user->id,
                    'approved_by' => $admin->id,
                ]);

            // Create pending prayers
            Prayer::factory(1)
                ->create([
                    'category_id' => $categories->random()->id,
                    'user_id' => $user->id,
                ]);
        }

        // Create participation
        $prayers = Prayer::active()->get();
        foreach ($prayers as $prayer) {
            $participantUsers = $users->random(rand(3, 8));
            foreach ($participantUsers as $participantUser) {
                PrayerParticipant::create([
                    'prayer_id' => $prayer->id,
                    'user_id' => $participantUser->id,
                    'participant_type' => $participantUser->user_type,
                    'is_first_time' => rand(0, 1),
                ]);
            }
            $prayer->update(['participant_count' => $prayer->participants()->count()]);
        }
    }
}
```

---

## Usage Examples

### Example 1: Get Public Prayer Board

```php
// PrayerController.php
public function publicBoard()
{
    $prayers = Prayer::forPublicBoard()
        ->paginate(20);

    return view('prayers.public-board', [
        'prayers' => $prayers,
        'categories' => PrayerCategory::activeAndOrdered()->get(),
    ]);
}
```

### Example 2: Submit Prayer Request

```php
public function store(StorePrayerRequest $request)
{
    $prayer = Prayer::createFromRequest(auth()->user(), [
        'category_id' => $request->category_id,
        'text' => $request->text,
    ]);

    return redirect()->back()->with('success', 'Prayer submitted for review');
}
```

### Example 3: Admin Approve Prayer

```php
public function approve(Prayer $prayer, ApprovePrayerRequest $request)
{
    $prayer->approve(auth()->user(), $request->expiry_days);

    return redirect()->back()->with('success', 'Prayer approved and published');
}
```

### Example 4: Lift Prayer (Participate)

```php
public function lift(Prayer $prayer)
{
    if (!$prayer->canUserParticipate(auth()->user())) {
        return response()->json(['error' => 'Cannot participate'], 403);
    }

    PrayerParticipant::recordParticipation($prayer, auth()->user());

    return response()->json([
        'success' => true,
        'participant_count' => $prayer->participant_count,
    ]);
}
```

### Example 5: Admin Dashboard - Pending Tab

```php
public function adminPending($month = null, $year = null, $category = null)
{
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;

    $query = Prayer::forAdminPending()
        ->inMonth($year, $month);

    if ($category) {
        $query->inCategory($category);
    }

    $prayers = $query->paginate(10);

    return view('admin.prayers.pending', [
        'prayers' => $prayers,
        'categories' => PrayerCategory::active()->get(),
        'month' => $month,
        'year' => $year,
    ]);
}
```

### Example 6: Nightly Jobs

```php
// app/Console/Commands/PrayerBoardMaintenance.php
public function handle()
{
    // Expire prayers
    $expired = Prayer::shouldExpire()->get();
    foreach ($expired as $prayer) {
        $prayer->expire();
    }
    $this->line("Expired {$expired->count()} prayers");

    // Delete rejected
    $deleted = Prayer::shouldDelete()->get();
    foreach ($deleted as $prayer) {
        $prayer->permanentlyDelete();
    }
    $this->line("Deleted {$deleted->count()} rejected prayers");

    // Sync participant counts
    $prayers = Prayer::active()->get();
    foreach ($prayers as $prayer) {
        $actualCount = $prayer->participants()->count();
        if ($actualCount !== $prayer->participant_count) {
            $prayer->update(['participant_count' => $actualCount]);
        }
    }
    $this->line("Synced participant counts");
}
```

---

**End of Laravel Models Document**
