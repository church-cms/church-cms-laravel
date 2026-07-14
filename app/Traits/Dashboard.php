<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Models\Attendance;
use App\Models\Bulletin;
use App\Models\Events;
use App\Models\Fund;
use App\Models\Gallery;
use App\Models\Group;
use App\Models\MediaFile;
use App\Models\Help;
use App\Models\Prayer;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use App\Models\EventAttendanceSession;

trait Dashboard
{
    public function adminDashboard(int $church_id, int $admin_id): array
    {
        $array = [];

        // All six member/guest counts in one query instead of six separate ones
        $stats = Cache::remember('memberStats' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByRole(5)
                ->where('church_id', $church_id)
                ->where('status', 'active')
                ->selectRaw("
                    SUM(membership_type = 'member')                          AS member_count,
                    SUM(membership_type = 'member' AND gender = 'male')      AS male_member_count,
                    SUM(membership_type = 'member' AND gender = 'female')    AS female_member_count,
                    SUM(membership_type = 'guest')                           AS guest_count,
                    SUM(membership_type = 'guest'  AND gender = 'male')      AS male_guest_count,
                    SUM(membership_type = 'guest'  AND gender = 'female')    AS female_guest_count
                ")
                ->first();
        });

        $array['memberCount']       = (int) ($stats->member_count       ?? 0);
        $array['maleMemberCount']   = (int) ($stats->male_member_count   ?? 0);
        $array['femaleMemberCount'] = (int) ($stats->female_member_count ?? 0);
        $array['guestCount']        = (int) ($stats->guest_count         ?? 0);
        $array['maleGuestCount']    = (int) ($stats->male_guest_count    ?? 0);
        $array['femaleGuestCount']  = (int) ($stats->female_guest_count  ?? 0);

        $array['longTimeMember'] = Cache::remember('longTimeMember' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return User::with(['userprofile.state', 'userprofile.city'])
                ->orderBy('created_at', 'asc')
                ->ByRole(5)->ByChurch($church_id)->ByStatus('active')
                ->take(4)->get();
        });

        $array['recentMember'] = Cache::remember('recentMember' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return User::with(['userprofile.state', 'userprofile.city'])
                ->orderBy('created_at', 'desc')
                ->ByRole(5)->ByChurch($church_id)->ByStatus('active')
                ->take(4)->get();
        });

        $array['eventCount'] = Cache::remember('eventCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Events::where('church_id', $church_id)->count();
        });

        $array['galleryCount'] = Cache::remember('galleryCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Gallery::where('church_id', $church_id)->count();
        });

        $array['fileCount'] = Cache::remember('fileCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return MediaFile::where('church_id', $church_id)->count();
        });

        $array['bulletinCount'] = Cache::remember('bulletinCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Bulletin::where('church_id', $church_id)->count();
        });

        $array['groupCount'] = Cache::remember('groupCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Group::where('church_id', $church_id)->count();
        });

        $array['fundlist'] = Cache::remember('fundlist' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])
                ->orderBy('authorised_at', 'DESC')->take(5)->get();
        });

        $array['upcomingEvents'] = Cache::remember('upcomingEvents' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Events::where('church_id', $church_id)
                ->where('start_date', '>=', date('Y-m-d H:i:s'))
                ->orderBy('start_date', 'asc')
                ->take(4)
                ->get();
        });
        // $event_attendance=return EventAttendanceSession::where('church_id', $church_id)
        //         ->orderBy('attendance_date', 'DESC')
        //         ->take(4)
        //         ->get();



        $array['EventAttendance'] = EventAttendanceSession::with('attendees')->where('church_id', $church_id)
            ->orderBy('attendance_date', 'DESC')
            ->take(4)
            ->get();

        $array['pendingPrayers'] = Cache::remember('pendingPrayers' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Prayer::forChurch($church_id)
                ->pending()
                ->with(['user', 'category'])
                ->latest()
                ->take(5)
                ->get();
        });

        $array['pendingPrayerCount'] = Cache::remember('pendingPrayerCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Prayer::forChurch($church_id)->pending()->count();
        });

        $array['pendingHelps'] = Cache::remember('pendingHelps' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Help::where('church_id', $church_id)
                ->where('status', 'pending')
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        });

        $array['pendingHelpCount'] = Cache::remember('pendingHelpCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Help::where('church_id', $church_id)->where('status', 'pending')->count();
        });

        $array['latestevent'] = Cache::remember('latestevent' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Events::where('church_id', $church_id)
                ->where('start_date', '>=', date('Y-m-d'))
                ->first();
        });

        $latestevent = $array['latestevent'];

        // Guard against no upcoming events
        $array['latest_date'] = $latestevent
            ? date('Y-m-d', strtotime('-1 day', strtotime($latestevent->start_date)))
            : null;

        $latest_date = $array['latest_date'];

        $array['absentMembers'] = $latest_date
            ? Cache::remember('absentMembers' . $church_id, env('CACHE_TIME'), function () use ($church_id, $latest_date) {
                return Attendance::where('church_id', $church_id)
                    ->where('is_present', 0)
                    ->where('date', '<=', $latest_date)
                    ->orderBy('date')->take(4)->get();
            })
            : collect();

        $array['total_fund'] = Cache::remember('total_fund' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])->sum('amount');
        });

        $array['funds'] = Cache::remember('funds' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])
                ->orderBy('authorised_at', 'DESC')
                ->get()
                ->groupBy(function ($fund) {
                    return Carbon::parse($fund->authorised_at)->format('M-y');
                })
                ->take(6);
        });

        $amountarray = [];
        foreach ($array['funds'] as $key => $groups) {
            foreach ($groups as $fund) {
                $amountarray[$key] = ($amountarray[$key] ?? 0) + $fund->amount;
            }
            if ($key == null) {
                $array['final'][] = ['y' => 0, 'label' => 0];
            } else {
                $array['final'][] = ['y' => $amountarray[$key], 'label' => $key];
            }
        }

        return $array;
    }
}
