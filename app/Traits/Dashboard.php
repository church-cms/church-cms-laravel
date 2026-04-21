<?php

/**
 * Trait for processing Dashboard
 */

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Models\Userprofile;
use App\Models\Attendance;
use App\Models\MediaFile;
use App\Models\Bulletin;
use App\Models\Gallery;
use App\Models\Events;
use App\Models\Group;
use App\Models\Fund;
use App\Models\User;
use Carbon\Carbon;

/**
 * Trait for dashboard data compilation and statistics
 *
 * Provides functionality for:
 * - Generating comprehensive church dashboard statistics
 * - Caching statistical data for performance
 * - Computing member and guest counts by various categories
 * - Aggregating event, gallery, and resource statistics
 * - Calculating fund and donation statistics
 * - Analyzing attendance patterns and trends
 *
 * @package App\Traits
 */
trait Dashboard
{
    /**
     * Compile comprehensive dashboard statistics for a church.
     *
     * Retrieves and caches various statistics including member counts (by gender),
     * guest counts, event details, gallery statistics, fund information, and
     * attendance analytics. All results are cached for improved performance.
     *
     * @param int $church_id The church ID to compile statistics for
     * @param int $admin_id The administrator ID viewing the dashboard
     *
     * @return array Comprehensive dashboard statistics array containing:
     *               - Member counts (total, male, female)
     *               - Guest counts (total, male, female)
     *               - Long-term and recent members
     *               - Event, gallery, file, bulletin, and group counts
     *               - Subscription information
     *               - Fund listings and total fund amounts
     *               - Latest event information
     *               - Absent members
     *               - Monthly fund aggregation data
     */
    public function adminDashboard(int $church_id, int $admin_id): array
    {
        $array = [];

        $array['memberCount'] = Cache::remember('memberCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return User::ByChurch($church_id)->ByRole(5)->ByMembershipType('member')->ByStatus('active')->count();
        });

        $array['maleMemberCount'] = Cache::remember('maleMemberCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByRole(5)->where([
                ['church_id', $church_id],
                ['membership_type', 'member'],
                ['gender', 'male'],
                ['status', 'active']
            ])->count();
        });

        $array['femaleMemberCount'] = Cache::remember('femaleMemberCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByRole(5)->where([
                ['church_id', $church_id],
                ['membership_type', 'member'],
                ['gender', 'female'],
                ['status', 'active']
            ])->count();
        });

        $array['guestCount'] = Cache::remember('guestCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByChurch($church_id)->ByRole(5)->ByMembershipType('guest')->ByStatus('active')->count();
        });

        $array['maleGuestCount'] = Cache::remember('maleGuestCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByRole(5)->where([
                ['church_id', $church_id],
                ['membership_type', 'guest'],
                ['gender', 'male'],
                ['status', 'active']
            ])->count();
        });

        $array['femaleGuestCount'] = Cache::remember('femaleGuestCount' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Userprofile::ByRole(5)->where([
                ['church_id', $church_id],
                ['membership_type', 'guest'],
                ['gender', 'female'],
                ['status', 'active']
            ])->count();
        });

        $array['longTimeMember'] = Cache::remember('longTimeMember' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return User::with('userprofile')->orderBy('created_at', 'asc')->ByRole(5)->ByChurch($church_id)->ByStatus('active')->take(4)->get();
        });

        $array['recentMember'] = Cache::remember('recentMember' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return User::with('userprofile')->orderBy('created_at', 'desc')->ByRole(5)->ByChurch($church_id)->ByStatus('active')->take(4)->get();
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
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])->orderBy('authorised_at', 'DESC')->take(5)->get();
        });

        $array['latestevent'] = Cache::remember('latestevent' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Events::where([['church_id', $church_id], ['start_date', '>=', date('Y-m-d')]])->first();
        });

        $latestevent = $array['latestevent'];

        $array['latest_date'] = Cache::remember('latest_date' . $church_id, env('CACHE_TIME'), function () use ($church_id, $latestevent) {
            return date('Y-m-d', strtotime('-1 day', strtotime($latestevent->start_date)));
        });

        $latest_date = $array['latest_date'];

        $array['absentMembers'] = Cache::remember('absentMembers' . $church_id, env('CACHE_TIME'), function () use ($church_id, $latest_date) {
            return Attendance::where([['church_id', $church_id], ['is_present', 0], ['date', '<=', $latest_date]])->orderBy('date')->take(4)->get();
        });

        $array['total_fund'] = Cache::remember('total_fund' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])->sum('amount');
        });

        $array['funds']  = Cache::remember('funds' . $church_id, env('CACHE_TIME'), function () use ($church_id) {
            return Fund::where([['church_id', $church_id], ['status', 'deposited']])->orderBy('authorised_at', 'DESC')->get()->groupBy([function ($fund) {
                return Carbon::parse($fund->authorised_at)->format('M-y');
            }])->take(6);
        });

        $amountarray = [];
        foreach ($array['funds'] as $key => $groups) {
            foreach ($groups as $fund) {
                $amountarray[$key] += $fund->amount;
            }
            if ($key == null) {
                $array['final'][] = array('y' => 0, 'label' => 0);
            } else {
                $array['final'][] = array('y' => $amountarray[$key], 'label' => $key);
            }
        }

        return $array;
    }
}
