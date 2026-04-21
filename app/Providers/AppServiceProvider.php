<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Observers\UserprofileObserver;
use App\Observers\MediaFileObserver;
use App\Observers\BulletinObserver;
use App\Observers\GalleryObserver;
use App\Observers\ChurchObserver;
use App\Observers\EventObserver;
use App\Models\MailinglistSubscriber;
use App\Models\Campaign;
use App\Observers\MailinglistSubscriberObserver;
use App\Observers\CampaignObserver;
use App\Models\CampaignEmail;
use App\Observers\CampaignEmailObserver;
use App\Observers\SubscriberObserver;
use App\Models\Subscribers;
use App\Observers\MailinglistObserver;
use App\Models\MailingList;
use App\Models\Email;
use App\Observers\EmailObserver;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Mail\Mailer;
use jdavidbakr\MailTracker\MailTracker;
use App\Models\GetResponse;
use App\Observers\GetResponseObserver;
use App\Observers\MailQueueObserver;
use App\Models\MailQueue;
use App\Models\Userprofile;
use App\Models\MediaFile;
use App\Models\Bulletin;
use App\Models\Gallery;
use App\Models\Events;
use App\Models\Church;
use Schema;
use Config;
use App\Models\ChurchDetail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Userprofile::observe(UserprofileObserver::class);
        MediaFile::observe(MediaFileObserver::class);
        Bulletin::observe(BulletinObserver::class);
        Gallery::observe(GalleryObserver::class);
        Events::observe(EventObserver::class);

        MailinglistSubscriber::observe(MailinglistSubscriberObserver::class);
        Campaign::observe(CampaignObserver::class);

        CampaignEmail::observe(CampaignEmailObserver::class);
        Subscribers::observe(SubscriberObserver::class);
        MailingList::observe(MailinglistObserver::class);
        Email::observe(EmailObserver::class);
        User::observe(UserObserver::class);
        GetResponse::observe(GetResponseObserver::class);
        MailQueue::observe(MailQueueObserver::class);

        Church::observe(ChurchObserver::class);

        //hide to receive mail
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            // Ignores notices and reports all other kinds... and warnings
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
            // error_reporting( E_ALL ^ E_WARNING );
            // Maybe this is enough
        }

        //dd(Auth::id());
        if (!\App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            $church = Church::first();

            $settings = ChurchDetail::where('church_id', $church->id)->whereIn('meta_key', ['site_title', 'site_description', 'site_keyword', 'favicon', 'church_logo', 'maintenance', 'login_status', 'register_status','header_code','footer_code', 'facebook_title','facebook_description','facebook_url','facebook_image','twitter_title','twitter_description','twitter_image','twitter_url'])->get();

            //$settings = Setting::all();

            foreach ($settings as $key => $setting) {
                $key = $setting->meta_key;

                if ($setting->meta_key == 'church_logo') {
                    $key = 'logo';
                    //Config::set('settings.login_status', 1);
                }
                //Config::set( 'settings.'.$key, $setting->meta_value );

                Config::set('settings.' . $key, $setting->meta_value);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('emails.maillist', function ($app, $parameters) {
            $smtp_host = $parameters['smtp_host'];
            $smtp_port = $parameters['smtp_port'];
            $smtp_username = $parameters['smtp_username'];
            $smtp_password = $parameters['smtp_password'];
            $smtp_encryption = $parameters['smtp_encryption'];

            $from_email = $parameters['from_email'];
            $from_name = $parameters['from_name'];

            $transport = new \Swift_SmtpTransport($smtp_host, $smtp_port);
            $transport->setUsername($smtp_username);
            $transport->setPassword($smtp_password);
            $transport->setEncryption($smtp_encryption);
            //dump($transport);
            $swift_mailer = new \Swift_Mailer($transport);
            //dump($app->get('view'));

            //dump($app);

            //$mailer = new Mailer($app->get('view'), $swift_mailer, $app->get('events'));
            $mailer = new Mailer('gego', $app->get('view'), $swift_mailer, $app->get('events'));

            // The trick
            $mailer->setQueue($app['queue']);
            $mailer->alwaysFrom($from_email, $from_name);
            $mailer->alwaysReplyTo($from_email, $from_name);
            $mailer->getSwiftMailer()->registerPlugin(new MailTracker());

            return $mailer;
        });
    }
}
