<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerParticipant extends Model
{
    protected $table = 'prayer_participants';

    const TYPE_MEMBER    = 'MEMBER';
    const TYPE_GUEST     = 'GUEST';
    const TYPE_ANONYMOUS = 'ANONYMOUS';

    protected $fillable = [
        'church_id',
        'prayer_id',
        'user_id',
        'participant_type',
        'anon_hash',
    ];

    // ===== RELATIONSHIPS =====

    public function prayer()
    {
        return $this->belongsTo(Prayer::class, 'prayer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    // ===== STATIC FACTORY =====

    /**
     * Record a "lift" (intercession) for a prayer.
     * Handles dedup and increments the denormalized count on Prayer.
     *
     * @param  Prayer      $prayer
     * @param  User|null   $user
     * @param  string      $type       MEMBER | GUEST | ANONYMOUS
     * @param  string|null $anonHash   SHA-256 of IP+UA for anonymous dedup
     * @return bool  true if lift was recorded (false if duplicate)
     */
    public static function lift(Prayer $prayer, $user, $type, $anonHash = null)
    {
        // Dedup check for authenticated users
        if ($user !== null) {
            $exists = static::where('prayer_id', $prayer->id)
                ->where('user_id', $user->id)
                ->exists();
            if ($exists) {
                return false;
            }

            static::create([
                'church_id'        => $prayer->church_id,
                'prayer_id'        => $prayer->id,
                'user_id'          => $user->id,
                'participant_type' => $type,
                'anon_hash'        => null,
            ]);
        } else {
            // Anonymous: dedup by hash
            if ($anonHash !== null) {
                $exists = static::where('prayer_id', $prayer->id)
                    ->where('anon_hash', $anonHash)
                    ->exists();
                if ($exists) {
                    return false;
                }
            }

            static::create([
                'church_id'        => $prayer->church_id,
                'prayer_id'        => $prayer->id,
                'user_id'          => null,
                'participant_type' => 'ANONYMOUS',
                'anon_hash'        => $anonHash,
            ]);
        }

        // Increment denormalized count
        $countColumn = 'anonymous_count';
        if ($type === 'MEMBER') {
            $countColumn = 'member_count';
        } elseif ($type === 'GUEST') {
            $countColumn = 'guest_count';
        }

        $prayer->increment($countColumn);

        return true;
    }

    // ===== QUERY SCOPES =====

    public function scopeForPrayer($query, $prayerId)
    {
        return $query->where('prayer_id', $prayerId);
    }

    public function scopeMembers($query)
    {
        return $query->where('participant_type', 'MEMBER');
    }

    public function scopeGuests($query)
    {
        return $query->where('participant_type', 'GUEST');
    }

    public function scopeAnonymous($query)
    {
        return $query->where('participant_type', 'ANONYMOUS');
    }
}
