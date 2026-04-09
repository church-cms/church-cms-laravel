<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// ─── WebBuilder Public Site ──────────────────────────────────────────────────
Route::group([
    'middleware' => \App\Http\Middleware\WebCmsContext::class,
    'namespace'  => 'WebBuilder',
], function () {
    Route::get('/',              'HomeController@index')->name('web.home');
    Route::get('/pages',                           'PageController@index')->name('web.pages');
    Route::get('/page/{category_slug}/{page_slug}', 'PageController@show')->name('web.page');
    Route::get('/posts',         'PostController@index')->name('web.posts');
    Route::get('/post/{id}',           'PostController@show')->name('web.post');
    Route::post('/post/{id}/comment', 'PostController@storeComment')->name('web.post.comment')->middleware('throttle:20,1');
    Route::post('/post/{id}/like',    'PostController@toggleLike')->name('web.post.like')->middleware('throttle:30,1');
    Route::post('/comment/{id}/like', 'PostController@toggleCommentLike')->name('web.comment.like')->middleware('throttle:30,1');
    Route::get('/faq',           'FaqController@index')->name('web.faq');
    Route::get('/events',        'EventController@index')->name('web.events');
    Route::get('/event/{id}',    'EventController@show')->name('web.event');
    Route::get('/gallery',       'GalleryController@index')->name('web.gallery');
    Route::get('/gallery/{id}',  'GalleryController@show')->name('web.gallery.show');
    Route::get('/sermons',       'SermonController@index')->name('web.sermons');
    Route::get('/sermon/{id}',   'SermonController@show')->name('web.sermon');
    Route::get('/prayer-requests',  'PrayerRequestController@index')->name('web.prayer');
    Route::post('/prayer-requests', 'PrayerRequestController@store')->name('web.prayer.store')->middleware('throttle:10,1');
    Route::post('/prayer-requests/{id}/lift', 'PrayerRequestController@lift')->name('web.prayer.lift')->middleware('throttle:30,1');
    Route::get('/help-requests',    'HelpRequestController@index')->name('web.help');
    Route::post('/help-requests',   'HelpRequestController@store')->name('web.help.store')->middleware('throttle:10,1');
    Route::get('/contact',          'ContactController@show')->name('web.contact');
    Route::post('/contact',         'ContactController@store')->name('web.contact.store')->middleware('throttle:5,1');
});

Auth::routes(['verify' => true, 'register' => false]);

//Impersonate as preacher
Route::get('/preacher/{id}/impersonate', 'Auth\ImpersonateController@impersonate')->middleware('auth', 'churchadmin');
Route::get('/preacher/impersonate/stop', 'Auth\ImpersonateController@stopImpersonate')->middleware('auth');

Route::post('/botman', 'Admin\BotmanMasterController@searchIndex');
Route::get('/botman/google', 'Admin\BotmanMasterController@nativeGoogle');
Route::get('botman/chat', function () {
    return view('botman_widget');
});

Auth::routes();

//Route::get('/checksms', 'ContactController@checksms');

/*video-chat room*/
Route::view('/video-chat-grid', 'pages.video.grid');
Route::view('/video-chat-collaboration', 'pages.video.collaboration');
Route::view('/video-chat-tile', 'pages.video.tile');
Route::view('/video-chat-presentation', 'pages.video.presentation');


//admin
Route::group(['prefix' => 'nova-api', 'middleware' => ['auth','nova'] ], function() {
    //change password - nova
    Route::get( '/changepassword', 'ChangePasswordController@ChangePassword' );
    Route::post( '/changepassword', 'ChangePasswordController@updateChangePassword' );
});

//subscription - nova
Route::get('/payment/subscription', 'Admin\PaymentController@Subscription');


//member
Route::group(['prefix' => 'member', 'middleware' => ['auth','churchmember'], 'namespace' =>'Member' ], function() {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/change-password', 'HomeController@changePassword')->name('member.change-password');
    Route::get('/familytree/{name}', 'HomeController@familytree');
    Route::get('/show/details/{name}', 'HomeController@showDetails');
    Route::get('/print/{name}','MembershipCardController@print');
});



//Reset Password for member

//Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
//Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

 Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
 Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
 Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
 Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//Email Verification for Member

Route::get('/emailverification/{token}', 'Auth\EmailVerificationController@emailverification');

//pricing

Route::get('/pricing','PricingController@create');
//about

//privacypolicy

//faq — now handled by WebBuilder\FaqController (see WebBuilder route group above)

//swotanalysis
Route::get('/swotanalysis','AboutController@show');

//terms
Route::get('/terms','AboutController@terms');

//contact — now handled by WebBuilder\ContactController (see WebBuilder route group above)

//permissions

Route::group(['prefix' => 'admin' , 'middleware' => ['auth'] , 'namespace' =>'Admin' ], function() {

    Route::get('/dashboard','DashboardController@index')->name( 'dashboard' );

    Route::get('/dashboard/birthdayUser', 'BirthdayController@birthdayUser');
    Route::get('/dashboard/birthday', 'BirthdayController@birthday');

    Route::get('/dashboard/anniversaryUser', 'BirthdayController@anniversaryUser');
    Route::get('/dashboard/anniversary', 'BirthdayController@anniversary');

    Route::get('/dashboard/event', 'DashboardController@event');
    Route::get('/dashboard/sermon', 'DashboardController@sermon');
    Route::get('/dashboard/absent', 'DashboardController@absent');

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-members'] , 'namespace' =>'Admin' ], function() {

    Route::get('/members','UserController@index');
    Route::get('/members/find','UserController@find');

    Route::get('/member/add', ['middleware' => ['permission:create-members'], 'uses' => 'MemberAddController@create']);
    Route::post('/member/add', ['middleware' => ['permission:create-members'], 'uses' => 'MemberAddController@store']);
    Route::post('/member/add/validationUser', ['middleware' => ['permission:create-members'], 'uses' => 'MemberAddController@validationUser']);
    Route::get('/member', ['middleware' => ['permission:create-members'], 'uses' => 'MemberAddController@member']);

    Route::get('/member/view/{name}','MembershipCardController@create');
        Route::get('/member/print/{name}','MembershipCardController@print');
        Route::get('/membershipCard/create','MembershipCardController@createAll');
        Route::get('/membershipCard/download/{usertype}','MembershipCardController@printAll');

    Route::get('/member/show/details/{name}', ['middleware' => ['permission:update-members'], 'uses' =>'MemberController@showdetails']);
    Route::get('/member/show/activity/{name}', ['middleware' => ['permission:update-members'], 'uses' =>'MemberController@showactivity']);
    Route::get('/member/show/{name}', ['middleware' => ['permission:update-members'], 'uses' => 'MemberController@show']);

    Route::get('/member/edit/{firstname}', ['middleware' => ['permission:update-members'], 'uses' => 'MemberEditController@edit']);
    Route::post('/member/edit/{firstname}', ['middleware' => ['permission:update-members'], 'uses' => 'MemberEditController@update']);
    Route::post('/getnotes', ['middleware' => ['permission:update-members'], 'uses' =>  'NotesController@index']);
    Route::delete('/notes/delete/{id}', ['middleware' => ['permission:update-members'], 'uses' => 'NotesController@delete']);
    Route::get('/notes/edit/{id}', ['middleware' => ['permission:update-members'], 'uses' => 'NotesController@edit']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-events'] , 'namespace' =>'Admin' ], function() {

    Route::get('/events', 'EventsController@index');
    Route::get('/events/show', 'EventsController@events');

    Route::post('/events/create', ['middleware' => ['permission:create-events'], 'uses' => 'EventsController@store']);

    Route::post('/events/changeevent/{id}', ['middleware' => ['permission:create-events'], 'uses' => 'EventsController@changeevent']);

    Route::post('/events/update/{id}', ['middleware' => ['permission:update-events'], 'uses' => 'EventsController@update']);
    Route::post('/events/validateedit/{id}', ['middleware' => ['permission:update-events'], 'uses' => 'EventsController@validateedit']);

    Route::get('/events/edit/{id}', ['middleware' => ['permission:update-events'], 'uses' => 'EventsController@edit']);
    Route::post('/getnotes', ['middleware' => ['permission:update-events'], 'uses' =>  'NotesController@index']);
    Route::delete('/notes/delete/{id}', ['middleware' => ['permission:update-events'], 'uses' => 'NotesController@delete']);
    Route::get('/notes/edit/{id}', ['middleware' => ['permission:update-events'], 'uses' => 'NotesController@edit']);

    //event_gallery
    Route::post('/upload/photos/{event_id}', ['middleware' => ['permission:update-events'], 'uses' => 'EventGalleryController@store']);

    Route::get('/display/photos/{event_id}', 'EventsController@showimage');
    Route::get('/getphoto/{event_id}', 'EventGalleryController@getPhoto');

    Route::get('/events/show/details/{id}', 'EventsController@show');
    Route::get('/events/showdetails/{id}', 'EventsController@showdetails');

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-bulletins'] , 'namespace' =>'Admin' ], function() {

    //Bulletins
    Route::get('/bulletins','BulletinsController@index');

    Route::get('/bulletin/get', ['middleware' => ['permission:create-bulletins'], 'uses' => 'BulletinsController@getData']);
    Route::get('/bulletin/create', ['middleware' => ['permission:create-bulletins'], 'uses' => 'BulletinsController@create']);
    Route::post('/bulletin/create', ['middleware' => ['permission:create-bulletins'], 'uses' => 'BulletinsController@store']);

    Route::get('/bulletin/download/{id}', ['middleware' => ['permission:view-bulletins'], 'uses' => 'BulletinsController@downloadattachments']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-files'] , 'namespace' =>'Admin' ], function() {

    Route::get('/videos', ['middleware' => ['permission:view-files'], 'uses' => 'VideoController@create']);
    Route::post('/videos', ['middleware' => ['permission:create-files'], 'uses' => 'VideoController@store']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-groups'] , 'namespace' =>'Admin' ], function() {

    Route::get('/groups','GroupsController@index');

    Route::get('/group/get', ['middleware' => ['permission:create-groups'], 'uses' =>'GroupsController@getData']);
    Route::get('/group/create', ['middleware' => ['permission:create-groups'], 'uses' =>'GroupsController@create']);
    Route::post('/group/create', ['middleware' => ['permission:create-groups'], 'uses' =>'GroupsController@store']);

    Route::get('/group/show/{id}', ['middleware' => ['permission:read-groups'], 'uses' =>'GroupsController@show']);
    Route::delete('/group/delete/{id}', ['middleware' => ['permission:delete-groups'], 'uses' =>'GroupsController@destroy']);

    Route::get('/group/showMember', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@index']);
    Route::get('/group/addMember/{group_id}', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@create']);
    Route::post('/group/addMember/{group_id}', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@store']);
    Route::delete('/group/removeMember/{id}', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@destroy']);
    Route::get('/group/editMember/{id}', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@edit']);
    Route::post('/group/editMember/{id}', ['middleware' => ['permission:update-groups'], 'uses' => 'GroupLinksController@update']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-gallery'] , 'namespace' =>'Admin' ], function() {

    Route::get('/gallery', 'GalleryController@index');

    Route::get('/gallery/create', ['middleware' => ['permission:create-gallery'], 'uses' => 'GalleryController@create']);
    Route::post('/gallery/store', ['middleware' => ['permission:create-gallery'], 'uses' => 'GalleryController@store']);

    Route::get('/gallery/edit/{id}', ['middleware' => ['permission:create-gallery'], 'uses' => 'GalleryController@edit']);
    Route::post('/gallery/edit/{id}', ['middleware' => ['permission:create-gallery'], 'uses' => 'GalleryController@update']);

    Route::get('/gallery/{id}', ['middleware' => ['permission:update-gallery'], 'uses' => 'GalleryController@show']);
    Route::get('/gallery/details/{id}', ['middleware' => ['permission:update-gallery'], 'uses' => 'GalleryController@showdetails']);
    Route::post('gallery/upload/photos/{gallery_id}', ['middleware' => ['permission:update-gallery'], 'uses' => 'PhotosController@store']);
    Route::get('gallery/display/photos/{gallery_id}', ['middleware' => ['permission:update-gallery'], 'uses' => 'PhotosController@showdetails']);

});

Route::group(['prefix' => 'preacher', 'middleware' => ['permission:read-sermons'] , 'namespace' =>'Preacher' ],
    function() {

    Route::get('/dashboard', 'DashboardController@index');

    //sermons
    Route::get('/sermon', 'SermonsController@index');

    Route::get('/sermon/create', ['middleware' => ['permission:create-sermons'], 'uses' => 'SermonsController@create']);
    Route::post('/sermon/save', ['middleware' => ['permission:create-sermons'], 'uses' => 'SermonsController@store']);

    Route::get('/links/{sermons_id}', ['middleware' => ['permission:create-sermons'], 'uses' => 'SermonLinkController@create']);
    Route::post('/links/{sermons_id}', ['middleware' => ['permission:create-sermons'], 'uses' => 'SermonLinkController@store']);

    Route::post('/links/update/{id}', ['middleware' => ['permission:update-sermons'], 'uses' => 'SermonLinkController@update']);
    Route::post('/links/validateedit/{id}', ['middleware' => ['permission:update-sermons'], 'uses' => 'SermonLinkController@validateedit']);
    Route::get('/links/edit/{id}', ['middleware' => ['permission:update-sermons'], 'uses' => 'SermonLinkController@edit']);

    Route::delete('/links/delete/{id}', ['middleware' => ['permission:delete-sermons'], 'uses' => 'SermonLinkController@destroy']);
    Route::get('/download/{id}','SermonLinkController@getDownload');

    //changepwd and avatar

    Route::get('/changepassword','PreacherController@ChangePassword');
    Route::post('/changepassword','PreacherController@updateChangePassword');

    Route::get('/changeavatar','PreacherController@changeavatar');
    Route::post('/changeavatar','PreacherController@updatechangeavatar');
    Route::get('/getavatar','PreacherController@getavatar');


    Route::get('/edit/{name}','PreacherController@edit');
    Route::get('/edit/list/{name}','PreacherController@create');
    Route::post('/edit/{name}','PreacherController@update');

    //activity
    Route::get('/activity','ActivityLogController@index');

});


Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-quotes'] , 'namespace' =>'Admin' ], function() {

    Route::get('/quote/add', ['middleware' => ['permission:create-quotes'], 'uses' => 'QuotesController@create']);
    Route::post('/quote/add', ['middleware' => ['permission:create-quotes'], 'uses' => 'QuotesController@store']);

});


Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-funds'] , 'namespace' =>'Admin' ], function() {

    Route::get('/funds', ['middleware' => ['permission:create-funds'], 'uses' => 'FundController@index']);
    Route::post('/funds', ['middleware' => ['permission:create-funds'], 'uses' => 'FundController@store']);
    Route::get('/funds/details/{id}', ['middleware' => ['permission:view-funds'], 'uses' => 'FundController@fundDetails']);
    Route::get('/funds/edit/{id}', ['middleware' => ['permission:update-funds'], 'uses' => 'FundController@edit']);
    Route::get('/funds/create/{id}', ['middleware' => ['permission:update-funds'], 'uses' => 'FundController@create']);
    Route::post('funds/update/{id}', ['middleware' => ['permission:update-funds'], 'uses' => 'FundController@update']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-preachers'] , 'namespace' =>'Admin' ], function() {

    Route::get('/preachers','PreacherController@index');
    Route::get('/preachers/find','PreacherController@find');

    Route::get('/preacher/add', ['middleware' => ['permission:create-preachers'], 'uses' => 'PreacherController@create']);
    Route::post('/preacher/add', ['middleware' => ['permission:create-preachers'], 'uses' => 'PreacherController@store']);
    Route::get('/preacher', ['middleware' => ['permission:create-preachers'], 'uses' => 'PreacherController@member']);

    Route::get('/preacher/show/details/{name}', ['middleware' => ['permission:update-preachers'], 'uses' =>'PreacherController@showdetails']);
    Route::get('/preacher/show/activity/{name}', ['middleware' => ['permission:update-preachers'], 'uses' =>'PreacherController@showactivity']);
    Route::get('/preacher/show/{name}', ['middleware' => ['permission:update-preachers'], 'uses' => 'PreacherController@show']);

    Route::get('/preacher/edit/{firstname}', ['middleware' => ['permission:update-preachers'], 'uses' => 'PreacherController@edit']);
    Route::post('/preacher/edit/{firstname}', ['middleware' => ['permission:update-preachers'], 'uses' => 'PreacherController@update']);
    Route::post('/getnotes', ['middleware' => ['permission:update-preachers'], 'uses' =>  'NotesController@index']);
    Route::delete('/notes/delete/{id}', ['middleware' => ['permission:update-preachers'], 'uses' => 'NotesController@delete']);
    Route::get('/notes/edit/{id}', ['middleware' => ['permission:update-preachers'], 'uses' => 'NotesController@edit']);

});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-reports'] , 'namespace' =>'Admin' ], function() {

    Route::get('/reports', ['middleware' => ['permission:read-reports'], 'uses' => 'ReportsController@report']);

    Route::get('/report/birthday', ['middleware' => ['permission:view-reports'], 'uses' =>'ReportsController@exportBirthday']);
    Route::get('/report/anniversary', ['middleware' => ['permission:view-reports'], 'uses' =>'ReportsController@exportAnniversary']);
    Route::get('/report/activeMembers', ['middleware' => ['permission:view-reports'], 'uses' =>'ReportsController@exportActiveMembers']);
    Route::get('/report/guestMembers', ['middleware' => ['permission:view-reports'], 'uses' =>'ReportsController@exportGuestMembers']);
    Route::get('/report/suspendedMembers', ['middleware' => ['permission:view-reports'], 'uses' =>'ReportsController@exportSuspendedMembers']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-payments'] , 'namespace' =>'Admin' ], function() {

    Route::get('/payment/index/{id}', ['middleware' => ['permission:create-payments'], 'uses' =>'PaymentController@index']);
    Route::post('/payment/response', ['middleware' => ['permission:create-payments'], 'uses' =>'PaymentController@response']);
});
Route::group(['prefix' => 'admin', 'middleware' => ['permission:read-payments'] ], function() {

    Route::get('/pricing', ['middleware' => ['permission:read-payments'], 'uses' => 'PricingController@create']);
});

