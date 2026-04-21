@php
    $isAdmin = auth()->user()->usergroup_id == 3;
    $user    = auth()->user();
@endphp
<ul class="list-reset tracking-wide font-navigation text-xs">
    <li class="py-2 px-3 {{ Request::segment('2') == 'dashboard' ? 'active' : '' }}">
        <a href="{{ url('admin/dashboard') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/dashboard-fill.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Dashboard</span>
        </a>
    </li>

    @if($isAdmin)
    <li class="py-2 px-3 {{ Request::segment('2') == 'churchdetails' ? 'active' : '' }}">
        <a href="{{ url('/admin/churchdetails/edit/'.$user->church->id) }}" class="flex items-center whitespace-no-wrap">
            <img src="{{ url('uploads/icons/church.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Church Details</span>
        </a>
    </li>
    @endif

    @if($isAdmin)
    @php
        $masterDataClass = '';
        $masterDataSegments = ['countries', 'country', 'states', 'state', 'cities', 'city'];
        if (in_array(\Request()->segment('2'), $masterDataSegments)) {
            $masterDataClass = 'active';
        }
    @endphp
    <li class="relative py-2 px-3 hover:bg-red-900 {{ $masterDataClass }}">
        <a href="#" class="flex items-center">
            <img src="{{ url('uploads/icons/settings.svg') }}" class="w-4 h-4">
            <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">Master Data</span>
            <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2">
        </a>
        <ul class="list-reset sites-sidebar">
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'countries' ? 'active' : '' }}">
                <a href="{{ url('/admin/countries') }}" class="flex items-center">
                    <span class="mx-3 whitespace-no-wrap">Countries</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'states' ? 'active' : '' }}">
                <a href="{{ url('/admin/states') }}" class="flex items-center">
                    <span class="mx-3 whitespace-no-wrap">States</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'cities' ? 'active' : '' }}">
                <a href="{{ url('/admin/cities') }}" class="flex items-center">
                    <span class="mx-3 whitespace-no-wrap">Cities</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if($isAdmin)
    <!-- start -->
    @php
        $class = '';
        $array = ['pages', 'page', 'page-categories', 'pageCategory', 'posts', 'post', 'post-categories', 'postCategory', 'settings', 'setting', 'faq', 'faq-categories', 'widgets', 'seodetail', 'google-analytics'];
        if (in_array(\Request()->segment('2'), $array)) {
            $class = 'active';
        }
    @endphp
    <li class="relative py-2 px-3 hover:bg-red-900 {{ $class }}">
        <a href="#" class="flex items-center">
            <img src="{{ url('uploads/icons/web-cms.svg') }}" class="w-4 h-4"
                style="filter: brightness(0) invert(1);">
            <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">WebCMS</span>
            <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2"> </span>
        </a>
        <ul class="list-reset sites-sidebar">
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'pages' ? 'active' : '' }} || {{ Request::segment('2') == 'page' ? 'active' : '' }}">
                <a href="{{ url('/admin/pages') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/pages.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Pages</span>
                </a>
            </li>
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'page-categories' ? 'active' : '' }}">
                <a href="{{ url('/admin/page-categories') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/pages.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Page Categories</span>
                </a>
            </li>
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'posts' ? 'active' : '' }} || {{ Request::segment('2') == 'post' ? 'active' : '' }}">
                <a href="{{ url('/admin/posts') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/posts.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Posts</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'post-categories' ? 'active' : '' }}">
                <a href="{{ url('/admin/post-categories') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/posts.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Post Categories</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'faq' && Request::segment('3') != 'categories' ? 'active' : '' }}">
                <a href="{{ url('admin/faq') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/faq.svg') }}" class="w-6 h-6"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-1 whitespace-no-wrap">FAQ</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment(2) == 'faq-categories' ? 'active' : '' }}">
                <a href="{{ url('/admin/faq-categories') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/faq.svg') }}" class="w-6 h-6"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-1 whitespace-no-wrap">FAQ Categories</span>
                </a>
            </li>
             <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'widgets' ? 'active' : '' }}">
                <a href="{{ url('/admin/widgets') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/widgets.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Code Snippets</span>
                </a>
            </li>
            <hr>
           <!--  <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'generalsettings' ? 'active' : '' }}">
                <a href="{{ url('/admin/settings/generalsettings') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/settings.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">General Settings</span>
                </a>
            </li> -->
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'maintenancesettings' ? 'active' : '' }}">
                <a href="{{ url('/admin/settings/maintenancesettings') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/maintenance.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Maintenance Settings</span>
                </a>
            </li>
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('3') == 'seodetail' ? 'active' : '' }}">
                <a href="{{ url('/admin/settings/seodetail') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/seo.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">SEO Settings</span>
                </a>
            </li>

            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'google-analytics' ? 'active' : '' }}">
                <a href="{{ url('/admin/google-analytics') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/google-analytics.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Google Analytics</span>
                </a>
            </li>

        </ul>
    </li>
    <!-- end -->
    @endif

    @php
        $showUsers = $isAdmin
            || $user->hasPermission('read-members')
            || $user->hasPermission('read-preachers');
    @endphp
    @if($showUsers)
    <!-- start -->
    @php
        $class = '';
        $array = ['members', 'member', 'guests', 'guest', 'preachers', 'preacher', 'subadmins', 'subadmin'];
        if (in_array(\Request()->segment('2'), $array)) {
            $class = 'active';
        }
    @endphp
    <li class="relative py-2 px-3 hover:bg-red-900 {{ $class }}">
        <a href="#" class="flex items-center">
            <img src="{{ url('uploads/icons/active-users.svg') }}" class="w-4 h-4">
            <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">Users</span>
            <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2"> </span>
        </a>
        <ul class="list-reset sites-sidebar">
            @if($isAdmin || $user->hasPermission('read-members'))
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'members' ? 'active' : '' }} || {{ Request::segment('2') == 'member' ? 'active' : '' }}">
                <a href="{{ url('/admin/members') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/multiple-users.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Members</span>
                </a>
            </li>
            @endif
            @if($isAdmin || $user->hasPermission('read-members'))
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'guests' ? 'active' : '' }} || {{ Request::segment('2') == 'guest' ? 'active' : '' }}">
                <a href="{{ url('/admin/guests') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/guest-user.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Guests</span>
                </a>
            </li>
            @endif
            @if($isAdmin || $user->hasPermission('read-preachers'))
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'preachers' ? 'active' : '' }}">
                <a href="{{ url('/admin/preachers') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/preacher.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Preachers</span>
                </a>
            </li>
            @endif
            @if($isAdmin)
            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'subadmins' ? 'active' : '' }}">
                <a href="{{ url('/admin/subadmins') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/subadmin.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Sub Admins</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    <!-- end -->
    @endif

    @if($isAdmin || $user->hasPermission('read-events'))
    <li class="py-2 px-3 {{ Request::segment('2') == 'events' ? 'active' : '' }}">
        <a href="{{ url('admin/events') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/calendar.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Calendar</span>
        </a>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-groups'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'groups' ? 'active' : '' }} && {{ Request::segment('2') == 'group' ? 'active' : '' }}">
        <a href="{{ url('admin/groups') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/multiple-users.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Groups</span>
        </a>
    </li>
    @endif

    <li class="py-2 px-3 {{ Request::segment('2') == 'video-conference' ? 'active' : '' }}">
        <a href="{{ url('/admin/video-conference') }}" class="flex items-center">
            <!--<img src="{{ url('uploads/icons/video_room.svg') }}" class="w-4 h-4"
                style="filter: brightness(0) invert(1);"> -->
            <img src="{{ url('uploads/icons/videocall.svg') }}" class="w-4 h-4"
                style="filter: brightness(0) invert(1);">
            <span class="mx-3 whitespace-no-wrap">Video Chat Room</span>
        </a>
    </li>

    @if($isAdmin || $user->hasPermission('read-members'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'messages' ? 'active' : '' }} || {{ Request::segment('2') == 'message' ? 'active' : '' }}">
        <a href="{{ url('/admin/messages') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/message-fill.svg') }}" class="w-4 h-4"
                style="filter: brightness(0) invert(1);">
            <span class="mx-3 whitespace-no-wrap">Messages</span>
        </a>
    </li>
    @endif


    @if($isAdmin)
    @php
        $class = '';
        $array = ['campaigns', 'emails', 'email', 'campaign', 'subscribers', 'subscriber', 'mailinglists', 'email-templates', 'mailinglist', 'mailqueues', 'mailqueue', 'smtps', 'smtp', 'newsletter', 'rules', 'rule', 'mails-delivered', 'mail-delivered', 'webhooks', 'webhook'];
        if (in_array(\Request()->segment('2'), $array)) {
            $class = 'active';
        }
    @endphp
    <li class="relative py-3 px-3 hover:font-semibold {{ $class }}">
        <a href="#" class="flex items-center whitespace-no-wrap text-white">
            <img src="{{ url('uploads/icons/email-blaster.svg') }}" class="w-4 h-4">
            <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">Email Blaster</span>
            <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2"> </span>
        </a>
        <ul class="list-reset sites-sidebar">
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'campaigns' ? 'active' : '' }} || {{ Request::segment('2') == 'campaign' ? 'active' : '' }}">
                <a href="{{ url('/admin/campaigns') }}" class="flex items-center text-white">
                    <img src="{{ url('uploads/icons/campaigns.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Campaigns</span>
                </a>
            </li>

            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'emails' ? 'active' : '' }} || {{ Request::segment('2') == 'email' ? 'active' : '' }}">
                <a href="{{ url('/admin/emails') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/email1.svg') }}" class="w-4 h-4 fill-current text-white"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Emails</span>
                </a>
            </li>

            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'subscribers' ? 'active' : '' }} || {{ Request::segment('2') == 'subscriber' ? 'active' : '' }}">
                <a href="{{ url('/admin/subscribers') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/subscribers.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Subscribers</span>
                </a>
            </li>

            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'mailinglists' ? 'active' : '' }} || {{ Request::segment('2') == 'mailinglist' ? 'active' : '' }}">
                <a href="{{ url('/admin/mailinglists') }}" class="flex items-center">
                    <img src="{{ url('/uploads/icons/mailinglist.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Mailing List</span>
                </a>
            </li>

            <!-- <li class="py-3 px-3 hover:font-semibold">
                <a href="{{ url('/admin/maileclipse/templates') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/template.svg') }}" class="w-4 h-4" style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Email Templates</span>
                </a>
            </li> -->

            <li class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'newsletter' ? 'active' : '' }}">
                <a href="{{ url('/admin/newsletter/send') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/newsletter.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="mx-3 whitespace-no-wrap">Send News Letter</span>
                </a>
            </li>
            @php
                $active_class = '';
                $array1 = ['rules', 'rule', 'mails-delivered', 'mail-delivered', 'mailqueues', 'mailqueue', 'smtps', 'smtp', 'webhooks', 'webhook'];
                if (in_array(\Request()->segment('2'), $array1)) {
                    $active_class = 'active';
                }
            @endphp
            <li class="py-3 px-3 hover:font-semibold {{ $active_class }}">
                <a href="#" class="flex items-center">
                    <img src="{{ url('uploads/icons/email-settings.svg') }}" class="w-4 h-4"
                        style="filter: brightness(0) invert(1);">
                    <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">Settings</span>
                    <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2"> </span>
                </a>
                <ul class="list-reset sites-sidebar" style="bottom: 0;top: auto;">
                    <li
                        class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'rules' ? 'active' : '' }} || {{ Request::segment('2') == 'rule' ? 'active' : '' }}">
                        <a href="{{ url('/admin/rules') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/rules.svg') }}" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);">
                            <span class="mx-3 whitespace-no-wrap">Rules</span>
                        </a>
                    </li>

                    <li
                        class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'mails-delivered' ? 'active' : '' }} || {{ Request::segment('2') == 'mail-delivered' ? 'active' : '' }}">
                        <a href="{{ url('/admin/mails-delivered') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/mail-delivered.svg') }}" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);">
                            <span class="mx-3 whitespace-no-wrap">Mails Delivered</span>
                        </a>
                    </li>

                    <li
                        class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'mailqueues' ? 'active' : '' }} || {{ Request::segment('2') == 'mailqueue' ? 'active' : '' }}">
                        <a href="{{ url('/admin/mailqueues') }}" class="flex items-center">
                            <img src="{{ url('/uploads/icons/mailqueue.svg') }}" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);">
                            <span class="mx-3 whitespace-no-wrap">Mail Queues</span>
                        </a>
                    </li>

                    <li
                        class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'smtps' ? 'active' : '' }} || {{ Request::segment('2') == 'smtp' ? 'active' : '' }}">
                        <a href="{{ url('/admin/smtps') }}" class="flex items-center">
                            <img src="{{ url('/uploads/icons/smtp.svg') }}" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);">
                            <span class="mx-3 whitespace-no-wrap">Smtps</span>
                        </a>
                    </li>

                    <li
                        class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'webhooks' ? 'active' : '' }} || {{ Request::segment('2') == 'webhook' ? 'active' : '' }}">
                        <a href="{{ url('/admin/webhooks') }}" class="flex items-center">
                            <img src="{{ url('uploads/icons/webhook.svg') }}" class="w-4 h-4"
                                style="filter: brightness(0) invert(1);">
                            <span class="mx-3 whitespace-no-wrap">Webhooks</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-files'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'mediafiles' ? 'active' : '' }} && {{ Request::segment('2') == 'mediafile' ? 'active' : '' }}">
        <a href="{{ url('/admin/mediafiles') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/video.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Media Files</span>
        </a>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-bulletins'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'bulletins' ? 'active' : '' }} && {{ Request::segment('2') == 'bulletin' ? 'active' : '' }}">
        <a href="{{ url('/admin/bulletins') }}" class="flex  items-center">
            <img src="{{ url('uploads/icons/bulletin.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Bulletin</span>
        </a>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-gallery'))
    <li class="py-2 px-3 {{ Request::segment('2') == 'gallery' ? 'active' : '' }}">
        <a href="{{ url('admin/gallery') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/gallery.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Gallery</span>
        </a>
    </li>
    @endif

    <!-- <li class="py-2 px-3 {{ Request::segment('2') == 'funds' ? 'active' : '' }} && {{ Request::segment('2') == 'fund' ? 'active' : '' }}">
        <a href="{{ url('admin/funds') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/giving.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Offerings</span>
        </a>
    </li> -->

    @php
        $showOfferings = $isAdmin
            || $user->hasPermission('read-payments')
            || $user->hasPermission('read-funds');
    @endphp
    @if($showOfferings)
    <!-- start -->
    @php
        $class = '';
        $array = ['payaccounts', 'payaccount', 'funds', 'fund'];
        if (in_array(\Request()->segment('2'), $array)) {
            $class = 'active';
        }
    @endphp
    <li class="relative py-2 px-3 hover:bg-red-900 {{ $class }}">
        <a href="#" class="flex items-center">
            <img src="{{ url('uploads/icons/giving.svg') }}" class="w-4 h-4">
            <span class="ml-3 whitespace-no-wrap flex items-center justify-between w-10/12">Offerings</span>
            <img src="{{ url('uploads/icons/right-arrow.svg') }}" class="w-2 h-2"> </span>
        </a>
        <ul class="list-reset sites-sidebar">
            @if($isAdmin || $user->hasPermission('read-payments'))
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'payaccounts' ? 'active' : '' }} || {{ Request::segment('2') == 'member' ? 'active' : '' }}">
                <a href="{{ url('/admin/payaccounts') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/multiple-users.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Payaccounts</span>
                </a>
            </li>
            @endif
            @if($isAdmin || $user->hasPermission('read-funds'))
            <li
                class="py-3 px-3 hover:font-semibold {{ Request::segment('2') == 'guests' ? 'active' : '' }} || {{ Request::segment('2') == 'guest' ? 'active' : '' }}">
                <a href="{{ url('/admin/funds') }}" class="flex items-center">
                    <img src="{{ url('uploads/icons/giving.svg') }}" class="w-4 h-4">
                    <span class="mx-3 whitespace-no-wrap">Funds</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    <!-- end -->
    @endif



    <li
        class="py-2 px-3 {{ in_array(Request::segment(2), ['prayerboard', 'prayercategories', 'prayercategory']) ? 'active' : '' }}">
        <a href="{{ url('/admin/prayerboard') }}" class="flex items-center whitespace-no-wrap">
            <img src="{{ url('uploads/icons/rosary.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Prayer Board</span>
        </a>
    </li>

    <li class="py-2 px-3 {{ Request::segment('2') == 'helps' ? 'active' : '' }}">
        <a href="{{ url('/admin/helps') }}" class="flex items-center whitespace-no-wrap">
            <img src="{{ url('uploads/icons/key.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Help Requests</span>
        </a>
    </li>

    @if($isAdmin || $user->hasPermission('read-sermons'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'sermons' ? 'active' : '' }} || {{ Request::segment('2') == 'sermon' ? 'active' : '' }}">
        <a href="{{ url('/admin/sermons') }}" class="flex  items-center">
            <img src="{{ url('uploads/icons/sermon.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Sermons</span>
        </a>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-quotes'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'quotes' ? 'active' : '' }} || {{ Request::segment('2') == 'quote' ? 'active' : '' }}">
        <a href="{{ url('/admin/quotes') }}" class="flex  items-center">
            <img src="{{ url('uploads/icons/library.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Quotes / Bible Verse</span>
        </a>
    </li>
    @endif

    @if($isAdmin || $user->hasPermission('read-reports'))
    <li
        class="py-2 px-3 {{ Request::segment('2') == 'reports' ? 'active' : '' }} && {{ Request::segment('2') == 'report' ? 'active' : '' }}">
        <a href="{{ url('/admin/reports') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/file.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Reports</span>
        </a>
    </li>
    @endif

    <li class="py-2 px-3 {{ Request::segment('2') == 'activity' ? 'active' : '' }}">
        <a href="{{ url('/admin/activity') }}" class="flex  items-center">
            <img src="{{ url('uploads/icons/activity-log.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Activity Logs</span>
        </a>
    </li>

    <li
        class="py-2 px-3 {{ Request::segment('2') == 'contacts' ? 'active' : '' }} && {{ Request::segment('2') == 'contact' ? 'active' : '' }}">
        <a href="{{ url('/admin/contacts') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/contact.svg') }}" class="w-4 h-4">
            <span class="mx-3 whitespace-no-wrap">Contact Requests</span>
        </a>
    </li>

    <li
        class="py-2 px-3 {{ Request::segment('2') == 'feedbacks' ? 'active' : '' }} || {{ Request::segment('2') == 'feedback' ? 'active' : '' }}">
        <a href="{{ url('/admin/feedbacks') }}" class="flex items-center">
            <img src="{{ url('uploads/icons/message-fill.svg') }}" class="w-4 h-4"
                style="filter: brightness(0) invert(1);">
            <span class="mx-3 whitespace-no-wrap">Feedbacks</span>
        </a>
    </li>


</ul>
