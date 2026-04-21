<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Models\ChurchDetail;
use App\Models\Church;
use App\Traits\Common;

class ChurchDetailsController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($church_id)
    {
        //
        $church = Church::where('id', $church_id)->first();
        $churchdetail = [];

        $churchdetails  = ChurchDetail::select('meta_key', 'meta_value')->where('church_id', $church->id)->get();
        $plucked  = $churchdetails->pluck('meta_value', 'meta_key');

        $churchdetail['church_name']     = ucwords($church->name);
        $churchdetail['church_logo']     = $plucked['church_logo'] === '-' ? '' : $this->getFilePath($plucked['church_logo']);
        $churchdetail['short_summary']   = $plucked['short_summary'] === '-' ? '' : $plucked['short_summary'];
        $churchdetail['long_summary']    = $plucked['long_summary'] === '-' ? '' : $plucked['long_summary'];
        $churchdetail['quotes']          = $plucked['quotes'] === '-' ? '' : $plucked['quotes'];
        $churchdetail['phone']           = $plucked['phone'] === '-' ? '' : $plucked['phone'];
        $churchdetail['email']           = $plucked['email'] === '-' ? '' : $plucked['email'];
        $churchdetail['address']         = $plucked['address'] === '-' ? '' : $plucked['address'];
        $churchdetail['latitude']        = $plucked['latitude'] === '-' ? '' : $plucked['latitude'];
        $churchdetail['longitude']       = $plucked['longitude'] === '-' ? '' : $plucked['longitude'];
        $churchdetail['website']         = $plucked['website'] === '-' ? '' : $plucked['website'];
        $churchdetail['facebook']        = $plucked['facebook'] === '-' ? '' : $plucked['facebook'];
        $churchdetail['twitter']         = $plucked['twitter'] === '-' ? '' : $plucked['twitter'];
        $churchdetail['instagram']       = $plucked['instagram'] === '-' ? '' : $plucked['instagram'];

        /* $churchdetail['seo_basic']['sitetitle']                = \config::get('settings.sitetitle');
        $churchdetail['seo_basic']['site_description']         = \config::get('settings.site_description');
        $churchdetail['seo_basic']['site_keyword']             = \config::get('settings.site_keyword');
        $churchdetail['seo_basic']['header_code']              = \config::get('settings.header_code');
        $churchdetail['seo_basic']['footer_code']              = \config::get('settings.footer_code');
        
        $churchdetail['seo_advanced']['facebook_title']        = \config::get('settings.facebook_title');
        $churchdetail['seo_advanced']['facebook_description']  = \config::get('settings.facebook_description');
        $churchdetail['seo_advanced']['facebook_url']          = \config::get('settings.facebook_url');
        $churchdetail['seo_advanced']['facebook_image']        = \config::get('settings.facebook_image') === null ? null:$this->getFilePath(\config::get('settings.facebook_image'));
        $churchdetail['seo_advanced']['twitter_title']         = \config::get('settings.twitter_title');
        $churchdetail['seo_advanced']['twitter_description']   = \config::get('settings.twitter_description');
        $churchdetail['seo_advanced']['twitter_url']           = \config::get('settings.twitter_url');
        $churchdetail['seo_advanced']['twitter_image']         = \config::get('settings.twitter_image') === null ? null:$this->getFilePath(\config::get('settings.twitter_image'));*/

        return $churchdetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialMedia($church_id)
    {
        //
        $church = Church::where('id', $church_id)->first();
        $churchdetail = [];

        $churchdetails  = ChurchDetail::select('meta_key', 'meta_value')->where('church_id', $church->id)->get();
        $plucked  = $churchdetails->pluck('meta_value', 'meta_key');

        $churchdetail['website']    = $plucked['website'] === '-' ? null : $plucked['website'];
        $churchdetail['facebook']   = $plucked['facebook'] === '-' ? null : $plucked['facebook'];
        $churchdetail['twitter']    = $plucked['twitter'] === '-' ? null : $plucked['twitter'];
        $churchdetail['instagram']  = $plucked['instagram'] === '-' ? null : $plucked['instagram'];

        return $churchdetail;
    }
}
