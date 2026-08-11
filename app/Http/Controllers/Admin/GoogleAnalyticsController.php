<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

/**
 * GoogleAnalyticsController
 *
 * Handles integration with Google Analytics for website traffic and visitor tracking.
 * Provides analytics dashboard and visitor metrics within the admin interface.
 * Gracefully handles missing analytics package with fallback empty data.
 *
 * @package App\Http\Controllers\Admin
 */
class GoogleAnalyticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

    }

    /**
     * Show the application google analytics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $emptyData = [
            'mostVisitedpage' => [],
            'pageViews' => [],
            'referrer' => [],
            'userType' => [],
            'browsers' => [],
        ];

        $periodClass = 'Spatie\\Analytics\\Period';

        if (!app()->bound('analytics') || !class_exists($periodClass)) {
            Log::warning('Google Analytics package is not available. Rendering analytics page with empty data.');

            return view('/admin/analytics/index', $emptyData);
        }

        try {
            $analytics = app('analytics');
            $period = $periodClass::days(7);

            return view('/admin/analytics/index', [
                'mostVisitedpage' => $analytics->fetchMostVisitedPages($period),
                'pageViews' => $analytics->fetchVisitorsAndPageViews($period),
                'referrer' => $analytics->fetchTopReferrers($period),
                'userType' => $analytics->fetchUserTypes($period),
                'browsers' => $analytics->fetchTopBrowsers($period),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch Google Analytics data.', [
                'message' => $e->getMessage(),
            ]);

            return view('/admin/analytics/index', $emptyData);
        }
    }

}
