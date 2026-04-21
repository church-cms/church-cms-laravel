<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prayer extends Model
{
    use SoftDeletes;

    protected $table = 'prayers';

    // Status constants
    const STATUS_PENDING     = 'PENDING';
    const STATUS_ACTIVE      = 'ACTIVE';
    const STATUS_ANSWERED    = 'ANSWERED';
    const STATUS_ENDED       = 'ENDED';
    const STATUS_REJECTED    = 'REJECTED';
    const STATUS_UNPUBLISHED = 'UNPUBLISHED';

    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_ACTIVE,
        self::STATUS_ANSWERED,
        self::STATUS_ENDED,
        self::STATUS_REJECTED,
        self::STATUS_UNPUBLISHED,
    ];

    protected $fillable = [
        'church_id',
        'category_id',
        'user_id',
        'text',
        'original_text',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
        'should_delete_at',
        'expiry_days',
        'expires_at',
        'pinned_at',
        'pinned_by',
        'answer_testimony',
        'answered_by',
        'answered_at',
        'member_count',
        'guest_count',
        'anonymous_count',
    ];

    protected $casts = [
        'approved_at'     => 'datetime',
        'rejected_at'     => 'datetime',
        'should_delete_at' => 'datetime',
        'expires_at'      => 'datetime',
        'pinned_at'       => 'datetime',
        'answered_at'     => 'datetime',
        'expiry_days'     => 'integer',
        'member_count'    => 'integer',
        'guest_count'     => 'integer',
        'anonymous_count' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    public function category()
    {
        return $this->belongsTo(PrayerCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function pinner()
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function participants()
    {
        return $this->hasMany(PrayerParticipant::class, 'prayer_id');
    }

    public function memberParticipants()
    {
        return $this->participants()->where('participant_type', 'MEMBER');
    }

    public function guestParticipants()
    {
        return $this->participants()->where('participant_type', 'GUEST');
    }

    public function anonymousParticipants()
    {
        return $this->participants()->where('participant_type', 'ANONYMOUS');
    }

    // ===== QUERY SCOPES =====

    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', self::STATUS_ANSWERED);
    }

    public function scopeEnded($query)
    {
        return $query->where('status', self::STATUS_ENDED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('status', self::STATUS_UNPUBLISHED);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeShouldExpire($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    public function scopeShouldDelete($query)
    {
        return $query->where('status', self::STATUS_REJECTED)
            ->whereNotNull('should_delete_at')
            ->where('should_delete_at', '<=', now());
    }

    public function scopeOrderByPinned($query)
    {
        return $query->orderByRaw('pinned_at IS NULL ASC')
            ->orderByDesc('pinned_at')
            ->orderByDesc('created_at');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeForPublicBoard($query)
    {
        return $query->active()
            ->with(['category', 'user'])
            ->orderByPinned();
    }

    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
    }

    // ===== ACCESSORS =====

    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }
        $remaining = now()->diffInDays($this->expires_at, false);
        return max(0, (int) $remaining);
    }

    public function getExpiryPercentageAttribute()
    {
        if (!$this->expires_at || !$this->created_at) {
            return 100;
        }
        $total = $this->created_at->diffInDays($this->expires_at);
        if ($total === 0) {
            return 100;
        }
        $elapsed  = $this->created_at->diffInDays(now());
        $percent  = ($elapsed / $total) * 100;
        return min(100, max(0, (int) $percent));
    }

    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'yellow';
            case self::STATUS_ACTIVE:
                return 'green';
            case self::STATUS_ANSWERED:
                return 'blue';
            case self::STATUS_ENDED:
                return 'gray';
            case self::STATUS_REJECTED:
                return 'red';
            case self::STATUS_UNPUBLISHED:
                return 'gray';
            default:
                return 'gray';
        }
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Pending Review';
            case self::STATUS_ACTIVE:
                return 'Active';
            case self::STATUS_ANSWERED:
                return 'Answered';
            case self::STATUS_ENDED:
                return 'Ended';
            case self::STATUS_REJECTED:
                return 'Rejected';
            case self::STATUS_UNPUBLISHED:
                return 'Unpublished';
            default:
                return ucfirst(strtolower($this->status));
        }
    }

    public function getSubmitterNameAttribute()
    {
        return $this->user ? $this->user->name : 'Anonymous';
    }

    public function getTotalParticipantCountAttribute()
    {
        return $this->member_count + $this->guest_count + $this->anonymous_count;
    }

    public function getIsPinnedAttribute()
    {
        return $this->pinned_at !== null;
    }

    // ===== LIFECYCLE METHODS =====

    public function approve(User $admin, $expiryDays)
    {
        $this->update([
            'status'      => self::STATUS_ACTIVE,
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'expiry_days' => $expiryDays,
            'expires_at'  => now()->addDays($expiryDays),
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties(['status' => 'PENDING → ACTIVE', 'expiry_days' => $expiryDays])
            ->useLog('prayer')
            ->log('Prayer approved');

        return $this;
    }

    public function reject(User $admin, $reason)
    {
        $deleteDays = (int) \DB::table('settings')
            ->where('key', 'prayer:autoDeleteRejectedAfterDays')
            ->value('value') ?: 7;

        $this->update([
            'status'           => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'rejected_by'      => $admin->id,
            'rejected_at'      => now(),
            'should_delete_at' => now()->addDays($deleteDays),
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties(['status' => 'PENDING → REJECTED', 'reason' => $reason])
            ->useLog('prayer')
            ->log('Prayer rejected');

        return $this;
    }

    public function markAnswered(User $user, $testimony = null)
    {
        $this->update([
            'status'           => self::STATUS_ANSWERED,
            'answered_by'      => $user->id,
            'answered_at'      => now(),
            'answer_testimony' => $testimony,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['status' => 'ACTIVE → ANSWERED', 'has_testimony' => !empty($testimony)])
            ->useLog('prayer')
            ->log('Prayer marked as answered');

        return $this;
    }

    public function extend(User $admin, $additionalDays)
    {
        $oldExpiry = $this->expires_at ? $this->expires_at->toDateTimeString() : null;
        $newExpiry = $this->expires_at
            ? $this->expires_at->copy()->addDays($additionalDays)
            : now()->addDays($additionalDays);

        $this->update([
            'expiry_days' => ($this->expiry_days ?? 0) + $additionalDays,
            'expires_at'  => $newExpiry,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties(['old_expires_at' => $oldExpiry, 'new_expires_at' => $newExpiry->toDateTimeString(), 'additional_days' => $additionalDays])
            ->useLog('prayer')
            ->log('Prayer expiry extended');

        return $this;
    }

    public function pin(User $admin)
    {
        $this->update([
            'pinned_at' => now(),
            'pinned_by' => $admin->id,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties(['pinned_at' => now()->toDateTimeString()])
            ->useLog('prayer')
            ->log('Prayer pinned');

        return $this;
    }

    public function unpin(User $admin)
    {
        $this->update([
            'pinned_at' => null,
            'pinned_by' => null,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties([])
            ->useLog('prayer')
            ->log('Prayer unpinned');

        return $this;
    }

    public function unpublish(User $admin)
    {
        $this->update(['status' => self::STATUS_UNPUBLISHED]);

        activity()
            ->performedOn($this)
            ->causedBy($admin)
            ->withProperties(['status' => 'ACTIVE → UNPUBLISHED'])
            ->useLog('prayer')
            ->log('Prayer unpublished');

        return $this;
    }
}
