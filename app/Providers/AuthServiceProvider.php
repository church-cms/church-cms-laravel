<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User'           => 'App\Policies\UserPolicy',
        'App\Models\Church'         => 'App\Policies\ChurchPolicy',
        'App\Models\State'          => 'App\Policies\StatePolicy',
        'App\Models\City'           => 'App\Policies\CityPolicy',
        'App\Models\Country'        => 'App\Policies\CountryPolicy',
        'App\Models\Contact'        => 'App\Policies\ContactPolicy',
        'App\Models\Group'          => 'App\Policies\GroupPolicy',
        'App\Models\GroupCategory'  => 'App\Policies\GroupCategoryPolicy',
        'App\Models\Userprofile'    => 'App\Policies\UserprofilePolicy',
        'App\Models\Usergroup'      => 'App\Policies\UsergroupPolicy',
        'App\Models\ActivityLog'    => 'App\Policies\ActivityLogPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Church admins (usergroup_id == 3) bypass all permission checks.
        // Sub-admins (usergroup_id == 4) are subject to normal permission gates.
        Gate::before(function ($user, $ability) {
            if ($user->usergroup_id == 3) {
                return true;
            }
        });

        Gate::define('event', function ($user, $event) {
            return $user->church_id == $event->church_id;
        });

        Gate::define('sermon', function ($user, $sermon) {
            return $user->church_id == $sermon->church_id;
        });

        Gate::define('files', function ($user, $file) {
            return $user->church_id == $file->church_id;
        });

        Gate::define('gallery', function ($user, $gallery) {
            return $user->church_id == $gallery->church_id;
        });

        Gate::define('photos', function ($user, $photos) {
            return $user->church_id == $photos->church_id;
        });

        Gate::define('member', function ($user, $member) {
            return $user->church_id == $member->church_id;
        });

        Gate::define('preacher', function ($user, $preacher) {
            return $user->church_id == $preacher->church_id;
        });

        Gate::define('bulletin', function ($user, $bulletin) {
            return $user->church_id == $bulletin->church_id;
        });

        Gate::define('fund', function ($user, $fund) {
            return $user->church_id == $fund->church_id;
        });

        Gate::define('group', function ($user, $group) {
            return $user->church_id == $group->church_id;
        });

        Gate::define('note', function ($user, $note) {
            return $user->church_id == $note->church_id;
        });

        Gate::define('photo', function ($user, $photo) {
            return $user->church_id == $photo->church_id;
        });

      
        // Gate::define('payment', function ($user, $id) {
        //     $plans = Plan::pluck('id')->toArray();
        //     foreach ($plans as $plan)
        //     {
        //         if($plan == $id)
        //         return true;
        //     }
        // });

        Gate::define('prayer', function ($user, $prayer) {
            return $user->church_id == $prayer->church_id;
        });

        Gate::define('help', function ($user, $help) {
            return $user->church_id == $help->church_id;
        });

        Gate::define('quote', function ($user, $quote) {
            return $user->church_id == $quote->church_id;
        });

        Gate::define('page', function ($user, $page) {
            return $user->church_id == $page->church_id;
        });

        Gate::define('page_attachment', function ($user, $page_attachment) {
            return $user->church_id == $page_attachment->page->church_id;
        });

        Gate::define('post', function ($user, $post) {
            return $user->church_id == $post->church_id;
        });
    }
}
