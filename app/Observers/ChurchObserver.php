<?php

namespace App\Observers;

use App\Models\ChurchDetail;
use App\Models\Church;
use Exception;
use Log;
use App\Models\User;

class ChurchObserver
{
    /**
     * Handle the church "created" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function created(Church $church)
    {
        //
        try {
            //$church = Church::where('id', $church->id)->get();
            $admin = User::where([['church_id', $church->id], ['usergroup_id', 3]])->first();
            $keys = [
                'church_logo' => '',
                'short_summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'long_summary' => 'Lorem ipsum dolor sit amet',
                'quotes' => 'Lorem ipsum dolor sit amet',
                'phone' => '-',
                'email' => $admin->email,
                'address' => $church->address,
                'latitude' => '9.9252007',
                'longitude' => '78.1197754',
                'website' => 'https://www.church.com',
                'facebook' => 'https://www.facebook.com/Test-page-112456983890996',
                'twitter' => 'https://twitter.com/Twitter',
                'instagram' => 'https://instagram.com/meme_coding?igshid=mw432c7aip81',
                'site_title' => 'church Social',
                'site_description' => 'Site Description',
                'site_keyword' => 'Site keyword',
                'favicon' => '-',
                'maintenance' => '0',
                'register_status' => '1',
                'login_status' => '1',
                'member_web_login' => '1',
                'guest_login' => '1',
                'guest_registration' => '1',
                'facebook_title' => 'Facebook Title',
                'facebook_description' => 'Facebook Des',
                'facebook_url' => 'Facebook URL',
                'facebook_image' => 'Facebook Image',
                'twitter_title' => 'Twitter title',
                'twitter_description' => 'Twitter des',
                'twitter_image' => 'Twitter image',
                'twitter_url' => 'Twitter url',
                'header_code' => 'Header Code',
                'footer_code' => 'Footer Code',
                'guest_register_captcha_status' => '0',
                'contact_captcha_status' => '0',


            ];

            foreach ($keys as $key => $value) {
                $detail = ChurchDetail::create([
                    'church_id'     =>  $church->id,
                    'meta_key'      =>  $key,
                    'meta_value'    =>  $value,
                ]);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the church "updated" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function updated(Church $church)
    {
        //
    }

    /**
     * Handle the church "deleted" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function deleted(Church $church)
    {
        //
    }

    /**
     * Handle the church "restored" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function restored(Church $church)
    {
        //
    }

    /**
     * Handle the church "force deleted" event.
     *
     * @param  \App\Models\Church  $church
     * @return void
     */
    public function forceDeleted(Church $church)
    {
        //
    }
}
