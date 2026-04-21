<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Faq;

/**
 * PageController
 *
 * Manages public content pages including news, privacy policy, and terms of service.
 * Handles dynamic page content display with proper localization support.
 * Note: Some functionality is commented and may need reactivation for news/ticket features.
 *
 * @package App\Http\Controllers
 * @deprecated Some methods contain commented code that may need review
 */
class PageController extends Controller
{
    /**
     * Load News
     *
     * @return \Illuminate\Http\Response
     */
    public function news()
    {
        return view('pages.news.newspage', []);
    }

    public function privacy()
    {
        if (!is_null(\Session::get('locale'))) {
            $lang = \Session::get('locale');
        } else {
            $lang = "en";
        }

        $pages  = Page::where([['language', $lang], ['slug', '=', 'policy']])->first();

        if (count($pages) > 0) {

            return view('pages.privacy.privacypage', [
                'pages' => $pages
            ]);
        } else {
            abort(403);
        }
    }

    public function terms()
    {
        if (!is_null(\Session::get('locale'))) {
            $lang = \Session::get('locale');
        } else {
            $lang = "en";
        }

        $terms  = Page::where([['language', $lang], ['slug', '=', 'terms-condition']])->first();

        if (count($terms) > 0) {

            return view('pages.privacy.termspage', [
                'terms' => $terms
            ]);
        } else {
            abort(403);
        }
    }




    public function getNews($slug)
    {
        $news  = News::where([['active', '=', '1'], ['slug', $slug]])->first();
        return view('pages.news.newsdetails', [
            'news' => $news,
        ]);
    }

    /**
     * Load FAQ Page
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {

        if (!is_null(\Session::get('locale'))) {
            $lang = \Session::get('locale');
        } else {
            $lang = "en";
        }

        $faq  = Faq::where([['active', 1], ['language', $lang]])->orderBy('order', 'ASC')->paginate(\Config::get('settings.pagecount'));
        return view('faq.faqpage', [
            'faq' => $faq,
        ]);
    }




    /**
     * display team profile detail
     * @return \Illuminate\Http\Response
     */
    public function teamProfile()
    {
        $team = Team::where([['status', 'active'], ['team_member', 'projectteam']])->orderBy('order', 'ASC')->paginate(5);
        return view('pages.teamprofile', [
            'team' => $team,
        ]);
    }
    /**
     * Show a single page based on slug
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!is_null(\Session::get('locale'))) {
            $lang = \Session::get('locale');
        } else {
            $lang = "en";
        }
        $pagedetails  = Page::where([['active', 1], ['language', $lang], ['slug', '=', $slug]])->first();

        if (count($pagedetails) > 0) {
            return view('pages.custompages', [
                'pagedetails' => $pagedetails,
                'slug' => $slug,
                'lang' => $lang,
            ]);
        } else {
            abort(403);
        }
    }
}
