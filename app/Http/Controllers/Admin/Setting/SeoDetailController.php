<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Requests\SeoDetailAdvancedRequest;
use App\Http\Requests\SeoDetailBasicRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\SettingProcess;
use App\Traits\Common;
use Exception;
use Log;

/**
 * SeoDetailController
 *
 * Manages SEO settings and metadata for the church website.
 * Handles both basic and advanced SEO configuration.
 * Uses SettingProcess trait for centralized settings management.
 *
 * @package App\Http\Controllers\Admin\Setting
 */
class SeoDetailController extends Controller
{
    use SettingProcess;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.settings.seodetailsettings');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $array = [];

        // $array['basic']['sitetitle']                = \config::get('settings.sitetitle');
        // $array['basic']['site_description']         = \config::get('settings.site_description');
        // $array['basic']['site_keyword']             = \config::get('settings.site_keyword');

        $array['basic']['header_code']              = \config::get('settings.header_code');
        $array['basic']['footer_code']              = \config::get('settings.footer_code');

        $array['advanced']['facebook_title']        = \config::get('settings.facebook_title');
        $array['advanced']['facebook_description']  = \config::get('settings.facebook_description');
        $array['advanced']['facebook_url']          = \config::get('settings.facebook_url');
        $array['advanced']['facebook_image']        = \config::get('settings.facebook_image') === null ? null : $this->getFilePath(\config::get('settings.facebook_image'));
        $array['advanced']['twitter_title']         = \config::get('settings.twitter_title');
        $array['advanced']['twitter_description']   = \config::get('settings.twitter_description');
        $array['advanced']['twitter_url']           = \config::get('settings.twitter_url');
        $array['advanced']['twitter_image']         = \config::get('settings.twitter_image') === null ? null : $this->getFilePath(\config::get('settings.twitter_image'));

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeoDetailBasicRequest $request)
    {
        //
        try {
            // $this->updatesettings('sitetitle',$request->sitetitle);
            // $this->updatesettings('site_description',$request->site_description);
            // $this->updatesettings('site_keyword',$request->site_keyword);
            $this->updatesettings('header_code', $request->header_code);
            $this->updatesettings('footer_code', $request->footer_code);

            $res['success'] = 'SEO Basic Settings Updated Successfully';
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(SeoDetailAdvancedRequest $request)
    {
        //
        try {
            $this->updatesettings('facebook_title', $request->facebook_title);
            $this->updatesettings('facebook_description', $request->facebook_description);
            $this->updatesettings('facebook_url', $request->facebook_url);
            $facebook = $request->file('facebook_image');
            if ($facebook) {
                $folder = Auth::user()->church_id . '/settings';
                $facebook_path = $this->uploadFile($folder, $facebook);
                $this->updatesettings('facebook_image', $facebook_path);
            } else {
                $this->updatesettings('facebook_image', \config::get('settings.facebook_image'));
            }

            $this->updatesettings('twitter_title', $request->twitter_title);
            $this->updatesettings('twitter_description', $request->twitter_description);
            $this->updatesettings('twitter_url', $request->twitter_url);
            $twitter = $request->file('twitter_image');
            if ($twitter) {
                $folder = Auth::user()->church_id . '/settings';
                $twitter_path = $this->uploadFile($folder, $twitter);
                $this->updatesettings('twitter_image', $twitter_path);
            } else {
                $this->updatesettings('twitter_image', \config::get('settings.twitter_image'));
            }

            $res['success'] = 'SEO Advanced Settings Updated Successfully';
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
