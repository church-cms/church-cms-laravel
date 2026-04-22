<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Requests\SettingGeneralRequest;
use App\Http\Controllers\Controller;
use App\Traits\SettingProcess;
use App\Traits\Common;
use Exception;
use Log;

/**
 * GeneralController
 *
 * Manages general church settings and configuration.
 * Handles display and update of church general settings.
 * Uses SettingProcess trait for centralized settings management.
 *
 * @package App\Http\Controllers\Admin\Setting
 */
class GeneralController extends Controller
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
        return view('admin.settings.generalsettings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingGeneralRequest $request)
    {
        try {
            $this->updatesettings('sitetitle', $request->sitetitle);
            $this->updatesettings('sitename', $request->sitename);

            if (($request->sitelogo) === null) {
                $this->updatesettings('sitelogo', (\config::get('settings.sitelogo')));
            } else {
                $name = $request->sitelogo->getClientOriginalName();
                $sitelogopath = $this->uploadFile('uploads/settings', $request->sitelogo, $name);
                $this->updatesettings('sitelogo', $sitelogopath);
            }

            if (($request->favicon) === null) {
                $this->updatesettings('favicon', (\config::get('settings.favicon')));
            } else {
                $name = $request->favicon->getClientOriginalName();
                $faviconpath = $this->uploadFile('uploads/settings', $request->favicon, $name);
                $this->updatesettings('favicon', $faviconpath);
            }
            return redirect()->back();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
