<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Controllers\Controller;


class SeoDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function basic()
    {
        //
        $array = [];

        $array['seo_basic']['sitetitle']                = \config::get('settings.sitetitle');
        $array['seo_basic']['site_description']         = \config::get('settings.site_description');
        $array['seo_basic']['site_keyword']             = \config::get('settings.site_keyword');
        $array['seo_basic']['header_code']              = \config::get('settings.header_code');
        $array['seo_basic']['footer_code']              = \config::get('settings.footer_code');

        return $array;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advanced()
    {
        //
        $array = [];

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
}
