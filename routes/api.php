<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Widget;

@include('angularapi.php');

// Public widget endpoint — no auth required
Route::get('/widget/{uid}', function ($uid) {
    $widget = Widget::where('slug', $uid)->first();
    if (! $widget) {
        return response()->json(['content' => ''], 404);
    }
    return response()->json(['content' => $widget->content]);
});
@include('guestapi.php');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Testing Purpose start

Route::get('/users', 'Api\TestController@index');

Route::get('/events','Api\TestController@events');

Route::get('/gallery','Api\TestController@gallery');

Route::get('/events/show/details/{id}','Api\EventsController@showdetails');

//end

//login
Route::post('/login', 'Api\LoginController@login');

//locations , churches list
Route::get('/locations', 'Api\ChurchController@locationList');

Route::get('/churches/{city_id}', 'Api\ChurchController@churchList');


//password reset

Route::post('/password/reset', 'Api\UserController@resetPassword');

Route::post('/password/store', 'Api\UserController@storePassword');

Route::post('/reset/check', 'Api\UserController@checkReset');

Route::post('/reset/change/password', 'Api\UserController@resetChangePassword');

//Route::get('/password/reset/form/{token}', 'Api\UserController@showResetForm');

//Route::post('/password/reset', 'Api\UserController@reset');

Route::group(['namespace' =>'Api' , 'middleware'=>['auth:sanctum']],
	function() {
		Route::post('/logout', 'LoginController@logout');
	});

Route::group([
	'prefix' => 'v1',
	'namespace' =>'Api' ,
	'middleware'=>['auth:sanctum']],
	function() {

	//Test Push Notification

	Route::post('/notification/create', 'TestController@notification');

	//members
	Route::get('/member/show/{id}', 'UserController@show');

	Route::post('/member/changePassword', 'UserController@changePassword');

	Route::post('/member/updatetoken', 'UserController@updatetoken');

	//Route::get('/member/resetPassword/{id}','UserController@resetPassword');

	Route::get('/member/get/marriage_status','UserprofileController@marriage_status');

	Route::get('/member/get/profession','UserprofileController@create');

	Route::get('/member/get/country','UserprofileController@country');

	Route::get('/member/get/state/{id}','UserprofileController@state');

	Route::get('/member/get/city/{id}','UserprofileController@city');


	Route::post('/member/edit/{id}', 'UserprofileController@update');

	//events

	Route::get('/event/show/{id}', 'EventsController@show');

	Route::get('/events/upcoming', 'EventsController@upcoming');//upcoming events

	Route::get('/events/past', 'EventsController@past');//past events

	Route::get('/events/gallery/show/{event_id}', 'EventGalleryController@showimage');

	//gallery

	Route::get('/gallery/show/{church_id}', 'GalleryController@showdetails');

	Route::get('gallery/view/photos/{gallery_id}', 'PhotosController@showdetails');

	//sermons

	Route::post('sermon/like','VotesController@like');

	Route::post('sermon/unlike','VotesController@unlike');

	Route::post('sermon/favorite','FavoritesController@favorites');

	Route::get('sermon/view/{church_id}','SermonsController@index');

	Route::get('sermon/show/{sermons_id}','SermonLinkController@showdetails');

	//video

	Route::get('/mediaFiles','MediaFilesController@showvideo');

	//bulletins

	Route::get('/bulletin/show', 'BulletinsController@show');

	//fund

	Route::get('/myFunds', 'FundController@myFunds');

	Route::get('/fund/list', 'FundController@list');

	Route::post('/add/fund', 'FundController@store');

	Route::get('/paymentgateway', 'PayaccountContorller@getlist');

	Route::get('/payaccount/{gateway_id}', 'PayaccountContorller@showdetails');

	//quotes

	Route::get('/quotes/show','QuotesController@index');

	//prayer_requests

	Route::get('/prayer_requests', 'PrayerRequestsController@index');

	Route::get('/prayer_requests/user', 'PrayerRequestsController@show');

	Route::post('/prayer_requests/create', 'PrayerRequestsController@store');

	//prayer_responses

	Route::get('/prayer_responses/{id}', 'PrayerResponsesController@index');

	Route::post('/prayer_responses/create/{id}', 'PrayerResponsesController@store');

	//helps

	Route::get('/help', 'HelpsController@index');

	Route::get('/helps/user', 'HelpsController@show'); //my helps

	Route::post('/helps/create', 'HelpsController@store');

	Route::post('/helps/close/{id}', 'HelpsController@update');

	//groups

	Route::get('/groups/list' , 'GroupsController@index');

    //video conference

    Route::get('/video-conference','VideoConferencesController@index');

    Route::get('/video-conference/{slug}/{user_id}','VideoConferencesController@show');

    //messages

    Route::get('/messages','SendMessageController@index');

    Route::get('/notifications','SendMessageController@notificationList');

    Route::post('/message/read/{id}','SendMessageController@readMessage');

    Route::post('/church/contact','ContactController@userStore');

    //feedbacks

    Route::get('/feedbacks','FeedbackController@index');

    Route::get('/feedback/category/list','FeedbackController@list');

    Route::post('/feedback/add','FeedbackController@store');

    //church detail

    Route::get('/church/details/{church_id}','ChurchDetailsController@show');
});
