<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Events Model
 *
 * Represents church events and activities.
 * Manages event details, scheduling, recurring events, and event-related operations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property string|null $select_type Event type classification
 * @property string|null $title Event title
 * @property string|null $description Event description
 * @property string|null $repeats Whether event repeats (yes/no)
 * @property string|null $freq Recurrence frequency (daily, weekly, monthly, yearly)
 * @property string|null $freq_term Recurrence term/pattern
 * @property string|null $location Event location
 * @property string|null $category Event category
 * @property string|null $organised_by Person/group organizing the event
 * @property string|null $image Event image/cover photo
 * @property \Carbon\Carbon|null $start_date Event start date and time
 * @property \Carbon\Carbon|null $end_date Event end date and time
 * @property bool $allDay Whether event spans full day
 * @property int|null $created_by User who created the event
 * @property int|null $updated_by User who last updated the event
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this event belongs to
 * @property-read \Illuminate\Database\Eloquent\Collection $notes Notes associated with this event
 * @property-read \Illuminate\Database\Eloquent\Collection $eventreminder Reminders for this event
 */
class Events extends Model
{
    //
    use SoftDeletes;
    use Common;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id',
        'select_type',
        'title',
        'description',
        'repeats',
        'freq',
        'freq_term',
        'days_of_week',
        'duration_minutes',
        'location',
        'category',
        'organised_by',
        'image',
        'start_date',
        'end_date',
        'allDay',
        'created_by',
        'updated_by',
        'publish_to_web',
        'enable_gallery',
        'enable_attendance',
        'attendance_scope',
        'attendance_group_id','month_type'
    ];

    protected $casts = [
        'days_of_week' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church', 'church_id');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\Notes', 'entity_id', 'id');
    }

    public function scopeByChurch($query, $church_id)
    {
        $query->where('church_id', $church_id);
        return $query;
    }

    public function eventreminder()
    {
        return $this->hasMany('App\Models\Reminder', 'entity_id', 'id')->where('entity_name', '=', 'App\\Models\\Events');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\EventGallery', 'event_id', 'id');
    }

    public function managers()
    {
        return $this->hasMany(EventManager::class, 'event_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(EventAttendanceSession::class, 'event_id');
    }

    public function getImagePathAttribute()
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return \Storage::disk('public')->url($this->image);
        }

        return $this->eventImagePath($this->category);
    }

    /**
     * Returns up to $limit upcoming occurrence dates for a recurring event,
     * skipping any dates already in $existingDates.
     * Returns an array of Carbon instances.
     */
    public function upcomingOccurrences(array $existingDates = [], int $limit = 5): array
    {
        if ($this->repeats != 1 || !$this->freq_term) {
            return [];
        }

        $start      = \Carbon\Carbon::parse($this->start_date)->startOfDay();
        $seriesEnd  = \Carbon\Carbon::parse($this->end_date)->endOfDay();
        $today      = \Carbon\Carbon::today();
        $from       = $today->gt($start) ? $today->copy() : $start->copy();
        $freq       = max(1, (int) $this->freq);
        $freqTerm   = $this->freq_term;
        $daysOfWeek = $this->days_of_week ? array_map('intval', (array) $this->days_of_week) : [];

        $results = [];
        $iter    = 0;
        $maxIter = 730;

        if ($freqTerm === 'week' && !empty($daysOfWeek)) {
            sort($daysOfWeek);
            // Sunday of the week containing start_date (dayOfWeek: 0=Sun … 6=Sat)
            $baseSunday = $start->copy()->subDays($start->dayOfWeek);
            $fromSunday = $from->copy()->subDays($from->dayOfWeek);

            // Advance baseSunday by freq-week steps until the next step would pass $fromSunday
            $cursor = $baseSunday->copy();
            while ($iter++ < $maxIter) {
                $next = $cursor->copy()->addWeeks($freq);
                if ($next->gt($fromSunday)) break;
                $cursor = $next;
            }

            while ($cursor->lte($seriesEnd) && count($results) < $limit && $iter < $maxIter) {
                $iter++;
                foreach ($daysOfWeek as $dow) {
                    $date = $cursor->copy()->addDays($dow);
                    if ($date->gte($from) && $date->lte($seriesEnd)) {
                        $dateStr = $date->toDateString();
                        if (!in_array($dateStr, $existingDates)) {
                            $results[] = $date;
                            if (count($results) >= $limit) break 2;
                        }
                    }
                }
                $cursor->addWeeks($freq);
            }
        } else {
            // day / week (no days_of_week) / month / year
            $cursor = $start->copy();
            // Fast-forward to the first occurrence on or after $from
            while ($cursor->lt($from) && $iter < $maxIter) {
                $iter++;
                switch ($freqTerm) {
                    case 'day':
                        $cursor->addDays($freq);
                        break;
                    case 'week':
                        $cursor->addWeeks($freq);
                        break;
                    case 'month':
                        $cursor->addMonths($freq);
                        break;
                    case 'year':
                        $cursor->addYears($freq);
                        break;
                    default:
                        return [];
                }
            }

            while ($cursor->lte($seriesEnd) && count($results) < $limit && $iter < $maxIter) {
                $iter++;
                $dateStr = $cursor->toDateString();
                if (!in_array($dateStr, $existingDates)) {
                    $results[] = $cursor->copy();
                }
                switch ($freqTerm) {
                    case 'day':
                        $cursor->addDays($freq);
                        break;
                    case 'week':
                        $cursor->addWeeks($freq);
                        break;
                    case 'month':
                        $cursor->addMonths($freq);
                        break;
                    case 'year':
                        $cursor->addYears($freq);
                        break;
                    default:
                        break 2;
                }
            }
        }

        return $results;
    }

    public function nextOccurrenceDate(): ?\Carbon\Carbon
    {
        $occ = $this->upcomingOccurrences([], 1);
        return $occ[0] ?? null;
    }
}
