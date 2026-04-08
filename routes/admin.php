<?php

use Illuminate\Support\Facades\Route;

    //dashboard
        //index
        Route::get('/dashboard', 'DashboardController@index')->name( 'dashboard' );

        //birthday
        Route::get('/dashboard/birthdayUser', 'BirthdayController@birthdayUser');
        Route::get('/dashboard/showBirthday', 'BirthdayController@showBirthday');
        Route::get('/dashboard/birthday', 'BirthdayController@birthday');
        Route::post('/dashboard/birthday', 'BirthdayController@birthdayMessage');

        //anniversary
        Route::get('/dashboard/anniversaryUser', 'BirthdayController@anniversaryUser');
        Route::get('/dashboard/showAnniversary', 'BirthdayController@showAnniversary');
        Route::get('/dashboard/anniversary', 'BirthdayController@anniversary');
        Route::post('/dashboard/anniversary', 'BirthdayController@anniversaryMessage');

        //notification

        Route::get('/notification/list', 'NotificationController@indexList');
        Route::get('/notifications', 'NotificationController@index');
        Route::post('/notification/read', 'NotificationController@store');
        Route::get('/notification/showList', 'NotificationController@showList');

        //event
        Route::get('/dashboard/event', 'DashboardController@event');

        //sermon
        Route::get('/dashboard/sermon', 'DashboardController@sermon');

    //contact
        //index
        Route::get('/contacts', 'ContactController@index');

        //show
        Route::get('/contact/show/{id}', 'ContactController@show');

    //Campaign (admin only - email marketing)
    Route::group(['middleware' => ['adminonly']], function() {
        //index
        Route::get('/campaigns', 'CampaignController@index');
        Route::get('/campaigns/list', 'CampaignController@list');

        //add
        Route::get('/campaign/add', 'CampaignController@create');
        Route::post('/campaign/add', 'CampaignController@store');

        //show
        Route::get('/campaign/show/{id}', 'CampaignController@show');

        //edit
        Route::get('/campaign/editList/{id}', 'CampaignController@editList');
        Route::get('/campaign/edit/{id}', 'CampaignController@edit');
        Route::post('/campaign/edit/{id}', 'CampaignController@update');

        //show
        Route::get('/campaign/{id}', 'CampaignController@show');

        //delete
        Route::delete('/campaign/delete/{id}', 'CampaignController@destroy');

    //Campaign Email
        //add
        Route::get('/campaign/attachemail/{id}', 'CampaignEmailController@create');
        Route::post('/campaign/attachemail/{id}', 'CampaignEmailController@store');

        //show
        Route::get('/campaign/attachemail/show/{id}', 'CampaignEmailController@show');

        //edit
        Route::get('/campaign/attachemail/edit/list/{id}', 'CampaignEmailController@editList');
        Route::get('/campaign/attachemail/edit/{id}', 'CampaignEmailController@edit');
        Route::post('/campaign/attachemail/edit/{id}', 'CampaignEmailController@update');

        //delete
        Route::delete('/campaign/attachemail/delete/{id}', 'CampaignEmailController@destroy');

    //Emails
        //index
        Route::get('/emails', 'EmailController@index');
        Route::get('/email/list', 'EmailController@list');

        //add
        Route::get('/email/add', 'EmailController@create');
        Route::post('/email/add', 'EmailController@store');

        //show
        Route::get('/email/show/{id}', 'EmailController@show');
        Route::get('/email/showdetails/{id}', 'EmailController@showDetails');
        //edit
        Route::get('/email/edit/{id}', 'EmailController@edit');
        Route::post('/email/edit/{id}', 'EmailController@update');

        //show
        Route::get('/email/{id}', 'EmailController@show');

        //delete
        Route::delete('/email/delete/{id}', 'EmailController@destroy');

        //Send test Mail
         Route::post('/sendtestmail', 'MailController@store');

        //Campaign Email

        Route::get('/campaignemail/attach/{id}', 'MailinglistController@create');
        Route::post('/campaignemail/attach/{id}', 'MailinglistController@store');

    //Mailing List
        //index
        Route::get('/mailinglists', 'MailinglistController@index');
        Route::get('/mailinglist/list', 'MailinglistController@list');

        //add
        Route::get('/mailinglist/add', 'MailinglistController@create');
        Route::post('/mailinglist/add', 'MailinglistController@store');

        //show
        Route::get('/mailinglist/show/{id}', 'MailinglistController@show');

        //edit
        Route::get('/mailinglist/editList/{id}', 'MailinglistController@editList');
        Route::get('/mailinglist/edit/{id}', 'MailinglistController@edit');
        Route::post('/mailinglist/edit/{id}', 'MailinglistController@update');

        //show
        Route::get('/mailinglist/{id}', 'MailinglistController@show');

        //delete
        Route::delete('/mailinglist/delete/{id}', 'MailinglistController@destroy');

        //detach subscriber
        Route::delete('/mailinglist/detachsubscriber/{subscriber_id}/{mailinglist_id}', 'MailinglistController@detachsubscriber');

        //attach subscriber
        Route::get('/mailinglist/attachsubscriber/{id}', 'AttachSubscriberController@create');
        Route::post('/mailinglist/attachsubscriber/{id}', 'AttachSubscriberController@store');

        //import csv
        Route::post('/mailinglist/importcsv/{id}', 'AttachSubscriberController@importcsv');

        //import subscriber
        Route::post('/mailinglist/importsubscriber', 'AttachSubscriberController@importsubscriber');
        Route::get('/mailinglist/view/{id}', 'AttachSubscriberController@show');

    //Subscriber
        //index
        Route::get('/subscribers', 'SubscribersController@index');
        Route::get('/subscriber/list', 'SubscribersController@list');

        //add
        Route::get('/subscriber/add', 'SubscribersController@create');
        Route::post('/subscriber/add', 'SubscribersController@store');

        //show
        Route::get('/subscriber/show/{id}', 'SubscribersController@show');
        Route::get('/subscriber/showdetails/{id}', 'SubscribersController@showDetails');

        //edit
        Route::get('/subscriber/edit/{id}', 'SubscribersController@edit');
        Route::post('/subscriber/edit/{id}', 'SubscribersController@update');

        //show
        //Route::get('/subscriber/{id}', 'SubscriberController@show');

        //delete
        Route::delete('/subscriber/delete/{id}', 'SubscribersController@destroy');

        //import
        Route::get('/subscriber', 'SubscribersController@import');
        Route::get('/subscriber/downloadformat', 'SubscribersController@downloadFormat');
        Route::post('/subscriber/importSubscribers', 'SubscribersController@importSubscribers');

        //export
        Route::get('/export', 'ExportSubscriberController@index');
        Route::get('/exportSubscribers', 'ExportSubscriberController@exportSubscribers');

        //maillist subscriber
        Route::get('/subscriber/attach/mailinglist/{id}', 'MaillistSubscriberController@create');
        Route::post('/subscriber/attach/mailinglist/{id}', 'MaillistSubscriberController@store');

    //Subscriber Widget
        Route::get('/subscribe/{mailinglist_slug}', 'SubscribeController@create');
        Route::post('/subscribe/{mailinglist_slug}', 'SubscribeController@store');
        Route::get('/unsubscribe/{mailinglist_slug}', 'UnSubscribeController@create');
        Route::get('/confirm', 'SubscribeController@confirm');

    //smtp
        //index
        Route::get('/smtps', 'SmtpController@index');
        Route::get('/smtp/list', 'SmtpController@list');

        //add
        Route::get('/smtp/add', 'SmtpController@create');
        Route::post('/smtp/add', 'SmtpController@store');

        //show
        Route::get('/smtp/show/{id}', 'SmtpController@show');

        //edit
        Route::get('/smtp/editList/{id}', 'SmtpController@editList');
        Route::get('/smtp/edit/{id}', 'SmtpController@edit');
        Route::post('/smtp/edit/{id}', 'SmtpController@update');

        //delete
        Route::delete('/smtp/delete/{id}', 'SmtpController@destroy');

    //Mail Queue
        //index
        Route::get('/mailqueues', 'MailQueueController@index');
        Route::get('/mailqueue/list', 'MailQueueController@list');

        //show
        Route::get('/mailqueue/show/{id}', 'MailQueueController@show');

        //edit
        Route::get('/mailqueue/edit/{id}', 'MailQueueController@edit');
        Route::post('/mailqueue/edit/{id}', 'MailQueueController@update');

        //delete
        Route::delete('/mailqueue/delete/{id}', 'MailQueueController@destroy');

    //News Letter
        //show
        Route::get('/newsletter/send', 'NewsLetterController@create');
        Route::post('/newsletter/send', 'NewsLetterController@store');
        Route::post('/member/subscribe/{name}', 'NewsLetterController@updateMemberStatus');
        Route::post('/members/subscribe', 'NewsLetterController@updateStatus');

    //Rules
        //index
        Route::get('/rules', 'EmailBlaster\RulesController@index');
        Route::get('/rule/list', 'EmailBlaster\RulesController@list');

        //add
        Route::get('/rule/add/list', 'EmailBlaster\RulesController@createList');
        Route::get('/rule/add', 'EmailBlaster\RulesController@create');
        Route::post('/rule/add', 'EmailBlaster\RulesController@store');

        //edit
        Route::get('/rule/edit/list/{id}', 'EmailBlaster\RulesController@editList');
        Route::get('/rule/edit/{id}', 'EmailBlaster\RulesController@edit');
        Route::post('/rule/edit/{id}', 'EmailBlaster\RulesController@update');

        //show
        Route::get('/rule/show/{id}', 'EmailBlaster\RulesController@show');

        //delete
        Route::delete('/rule/delete/{id}', 'EmailBlaster\RulesController@destroy');

    //Mails Delivered
        //index
        Route::get('/mails-delivered', 'EmailBlaster\MailsDeliveredController@index');
        Route::get('/mail-delivered/list', 'EmailBlaster\MailsDeliveredController@list');

        //show
        Route::get('/mail-delivered/show/{id}', 'EmailBlaster\MailsDeliveredController@show');

    //Webhooks
        //index
        Route::get('/webhooks', 'EmailBlaster\WebhooksController@index');
        Route::get('/webhook/list', 'EmailBlaster\WebhooksController@list');

        //add
        Route::get('/webhook/add/list', 'EmailBlaster\WebhooksController@createList');
        Route::get('/webhook/add', 'EmailBlaster\WebhooksController@create');
        Route::post('/webhook/add', 'EmailBlaster\WebhooksController@store');

        //edit
        Route::get('/webhook/edit/list/{id}', 'EmailBlaster\WebhooksController@editList');
        Route::get('/webhook/edit/{id}', 'EmailBlaster\WebhooksController@edit');
        Route::post('/webhook/edit/{id}', 'EmailBlaster\WebhooksController@update');

        //show
        Route::get('/webhook/show/{id}', 'EmailBlaster\WebhooksController@show');

        //delete
        Route::delete('/webhook/delete/{id}', 'EmailBlaster\WebhooksController@destroy');
    });

    //church details
    Route::group(['middleware' => ['adminonly']], function() {
        //index
        Route::get('/churchdetails', 'ChurchDetailsController@index');

        //add
        Route::get('/churchdetails/add', 'ChurchDetailsController@create');
        Route::post('/churchdetails/add', 'ChurchDetailsController@store');

        //edit
        Route::get('/churchdetails/edit/{church_id}','ChurchDetailsController@edit');
        Route::post('/churchdetails/edit/{church_id}','ChurchDetailsController@update');
    });

    //master data
    Route::group(['middleware' => ['adminonly'], 'namespace' => 'MasterData'], function () {
        Route::get('/countries',             'CountryController@index');
        Route::post('/countries/bulk',        'CountryController@bulk');
        Route::get('/country/create',        'CountryController@create');
        Route::post('/country/create',       'CountryController@store');
        Route::get('/country/edit/{id}',     'CountryController@edit');
        Route::post('/country/edit/{id}',    'CountryController@update');
        Route::delete('/country/delete/{id}','CountryController@destroy');

        Route::get('/states',                'StateController@index');
        Route::post('/states/bulk',          'StateController@bulk');
        Route::get('/state/create',          'StateController@create');
        Route::post('/state/create',         'StateController@store');
        Route::get('/state/edit/{id}',       'StateController@edit');
        Route::post('/state/edit/{id}',      'StateController@update');
        Route::delete('/state/delete/{id}',  'StateController@destroy');

        Route::get('/cities',                'CityController@index');
        Route::get('/city/create',           'CityController@create');
        Route::post('/city/create',          'CityController@store');
        Route::get('/city/edit/{id}',        'CityController@edit');
        Route::post('/city/edit/{id}',       'CityController@update');
        Route::delete('/city/delete/{id}',   'CityController@destroy');
        Route::post('/cities/bulk',          'CityController@bulk');

        // AJAX helpers
        Route::get('/ajax/states',           'StateController@ajaxByCountry');
    });

    //video conference
        //index
        Route::get('/video-conference', 'VideoConferencesController@index');

        //add
        Route::get('/video-conference/list', 'VideoConferencesController@list');
        Route::get('/video-conference/create', 'VideoConferencesController@create');
        Route::post('/video-conference/save', 'VideoConferencesController@store');

        //show
        Route::get('/video-conference/{slug}', 'VideoConferencesController@show');

        //edit
        Route::get('/video-conference/editList/{id}', 'VideoConferencesController@editList');
        Route::get('/video-conference/edit/{id}', 'VideoConferencesController@edit');
        Route::post('/video-conference/edit/{id}', 'VideoConferencesController@update');

        //delete
        Route::delete('/video-conference/remove/{id}', 'VideoConferencesController@remove');

        //widgets
        //index
        Route::get('/widgets', 'WidgetController@index');
        Route::get('/widgets/{id}/edit', 'WidgetController@edit');
        Route::post('/widgets/{id}/update', 'WidgetController@update');
        Route::get('/widgets/create', 'WidgetController@create');
        Route::post('/widgets/create', 'WidgetController@store');
        //invites
        Route::get('/video-conference/manage-invites/{id}', 'VideoConferencesController@invites');
        Route::delete('/video-conference/remove-users/{id}', 'VideoConferencesController@removeUsers');
        Route::post('/video-conference/status/{id}', 'VideoConferencesController@statusUpdate');
        Route::get('/video-conference/recordings/{id}', 'VideoConferencesController@recordings');
        Route::get('/video-conference/add-invites/{id}', 'VideoConferencesController@addinvites');
        Route::post('/video-conference/save-invites/{id}', 'VideoConferencesController@saveinvites');

    //notes

    Route::post('/getnotes', 'NotesController@index');
    Route::delete('/notes/delete/{id}', 'NotesController@delete');
    Route::get('/notes/edit/{id}', 'NotesController@edit');

    Route::get('/notes', 'NotesController@create');
    Route::post('/notes', 'NotesController@store');

    //activity_log

    Route::get('/activity','ActivityLogController@index');

    //faq (admin only)
    Route::group(['middleware' => ['adminonly']], function() {
        Route::post('/faq/category/create','FaqCategoryController@store');

        //faq
        //index
        Route::get('/faq/list','FaqController@list');
        Route::get('/faq','FaqController@index');

        //add
        Route::get('/faq/addList','FaqController@createList');
        Route::get('/faq/create','FaqController@create');
        Route::post('/faq/create','FaqController@store');

        //show
        Route::get('/faq/show/{id}','FaqController@show');

        //edit
        Route::get('/faq/editList/{id}','FaqController@editList');
        Route::get('/faq/edit/{id}','FaqController@edit');
        Route::post('/faq/edit/{id}','FaqController@update');

        //delete
        Route::delete('/faq/delete/{id}','FaqController@destroy');
    });

    //members
    Route::group(['middleware' => ['permission:read-members']], function() {
        //index
        Route::get('/members','UserController@index');
        Route::get('/members/find','UserController@find');
        Route::get('/member/filter/list','UserController@filterList');

        //add
        Route::get('/member','MemberAddController@member');
        Route::get('/member/add','MemberAddController@create');
        Route::post('/member/add','MemberAddController@store');
        Route::post('/member/add/validationUser','MemberAddController@validationUser');

        //show
        Route::get('/member/show/familytree/{name}','MemberController@familytree');
        Route::get('/member/show/family/{name}','MemberController@showFamily');
        Route::get('/member/show/details/{name}','MemberController@showDetails');
        Route::get('/member/show/activity/{name}','MemberController@showActivity');
        Route::get('/member/show/group/{name}','MemberController@showGroups');
        Route::get('/member/show/message/{name}','MemberController@showMessages');
        Route::get('/member/show/{name}', 'MemberController@show');

        //edit
        Route::get('/member/editList/{name}','MemberEditController@editList');
        Route::get('/member/edit/{name}','MemberEditController@edit');
        Route::post('/member/edit/{name}','MemberEditController@update');

        //exit
        Route::get('/member/exit/{name}','UserController@exitCreate');
        Route::post('/member/exit/{name}','UserController@exitStore');

        //update status
        Route::post('/member/updateStatus/{name}','UserController@updateStatus');

        //reset password
        Route::post('/member/resetPassword/{id}','UserController@resetPassword');

        //email verification
        Route::get('/member/{id}/verificationcode', 'UserController@emailVerification');

        //delete
        Route::delete('/member/delete/{name}','UserController@destroy');

        //message
        Route::get('/messages', 'SendMessageController@index');
        Route::get('/message/show/{id}', 'SendMessageController@show');
        Route::post('/member/sendMessage/{name}','SendMessageController@store');
        Route::post('/member/sendMessageToAll', 'SendMessageController@memberstore');
    });

    //guests
    Route::group(['middleware' => ['permission:read-members']], function() {
        //index
        Route::get('/guests','GuestsController@index');
        Route::get('/guests/find','GuestsController@find');
        Route::get('/guest/filter/list','GuestsController@filterList');

        //add
        Route::get('/guest','GuestAddController@member');
        Route::get('/guest/add','GuestAddController@create');
        Route::post('/guest/add','GuestAddController@store');
        Route::post('/guest/add/validationUser','GuestAddController@validationUser');

        //show
        Route::get('/guest/show/familytree/{name}','GuestDetailsController@familytree');
        Route::get('/guest/show/family/{name}','GuestDetailsController@showFamily');
        Route::get('/guest/show/details/{name}','GuestDetailsController@showDetails');
        Route::get('/guest/show/activity/{name}','GuestDetailsController@showActivity');
        Route::get('/guest/show/group/{name}','GuestDetailsController@showGroups');
        Route::get('/guest/show/message/{name}','GuestDetailsController@showMessages');
        Route::get('/guest/show/{name}', 'GuestDetailsController@show');

        //edit
        Route::get('/guest/editList/{name}','GuestEditController@editList');
        Route::get('/guest/edit/{name}','GuestEditController@edit');
        Route::post('/guest/edit/validationGuestEdit/{name}','GuestEditController@validationGuestEdit');
        Route::post('/guest/edit/{name}','GuestEditController@update');

        //exit
        Route::get('/guest/exit/{name}','GuestsController@exitCreate');
        Route::post('/guest/exit/{name}','GuestsController@exitStore');

        //update status
        Route::post('/guest/updateStatus/{name}','GuestsController@updateStatus');

        //reset password
        Route::post('/guest/resetPassword/{id}','GuestsController@resetPassword');

        //email verification
        Route::get('/guest/{id}/verificationcode', 'GuestsController@emailVerification');

        //delete
        Route::delete('/guest/delete/{name}','GuestsController@destroy');

        //export
        Route::get('/exportGuests', 'ExportMemberController@exportGuests');
    });

    //preacher
    Route::group(['middleware' => ['permission:read-preachers']], function() {
        //index
        Route::get('/preacher','PreacherController@member');
        Route::get('/preachers','PreacherController@index');
        Route::get('/preachers/find','PreacherController@find');

        //add
        Route::get('/preacher/add','PreacherController@create');
        Route::post('/preacher/add','PreacherController@store');

        //show
        Route::get('/preacher/show/details/{name}','PreacherController@showDetails');
        Route::get('/preacher/show/{name}', 'PreacherController@show');

        //edit
        Route::get('/preacher/edit/list/{name}','PreacherController@editList');
        Route::get('/preacher/edit/{name}','PreacherController@edit');
        Route::post('/preacher/edit/{name}','PreacherController@update');
    });

    //subadmin
    Route::group(['middleware' => ['adminonly']], function() {
        //index
        Route::get('/subadmins/find','SubAdminController@find');
        Route::get('/subadmins','SubAdminController@index');
        Route::get('/subadmin/filter/list','SubAdminController@filterList');

        //add
        Route::get('/subadmin/add','SubAdminController@create');
        Route::post('/subadmin/add/validationUser','SubAdminController@validationUser');
        Route::post('/subadmin/add','SubAdminController@store');

        //show
        Route::get('/subadmin/show/{name}', 'SubAdminController@show');

        //edit
        Route::get('/subadmin/editList/{name}','SubAdminController@editList');
        Route::get('/subadmin/edit/{name}','SubAdminController@edit');
        Route::post('/subadmin/edit/validationUser/{name}', 'SubAdminController@editValidationUser' );
        Route::post('/subadmin/edit/{name}','SubAdminController@update');

        //permissions
        Route::get('/subadmin/permissions/{name}','SubAdminController@getPermissions');
        Route::post('/subadmin/permissions/{name}','SubAdminController@updatePermissions');
    });

    //recurring events
    Route::group(['middleware' => ['permission:read-events']], function() {
        Route::get('/events', 'EventsController@index');
    Route::get('/events/show', 'EventsController@events');
    Route::post('/events/create', 'EventsController@store');
    Route::post('/events/update/{id}', 'EventsController@update');
    Route::post('/events/changeevent/{id}', 'EventsController@changeevent');
    Route::post('/events/validateedit/{id}', 'EventsController@validateedit');
    Route::get('/events/edit/{id}', 'EventsController@edit');
    Route::get('/events/show/details/{id}', 'EventsController@show');
    Route::get('/events/showdetails/{id}', 'EventsController@showdetails');
    Route::get('/events/details/{id}','EventsController@details');
    Route::delete('/events/delete/{id}','EventsController@destroy');
        Route::get('/event/showAttendees/{id}/{status}','EventsController@showAttendees');

    //event_gallery
    Route::post('/upload/photos/{event_id}','EventGalleryController@store');
    Route::get('/display/photos/{event_id}', 'EventsController@showimage');
    Route::get('/getphoto/{event_id}', 'EventGalleryController@getPhoto');
    Route::delete('/event/photo/delete/{id}', 'EventGalleryController@destroy');
    });

    //Settings
    Route::group(['middleware' => ['adminonly']], function() {
        Route::get('/settings/generalsettings','Setting\GeneralController@create');
    Route::post('/settings/generalsettings','Setting\GeneralController@store');
    Route::get('/settings/maintenancesettings','Setting\MaintenanceController@create');
    Route::post('/settings/maintenancesettings','Setting\MaintenanceController@store');

    Route::get('/settings/seodetail','Setting\SeoDetailController@create');
    Route::get('/settings/seo/list','Setting\SeoDetailController@list');
    Route::post('/settings/basicseo','Setting\SeoDetailController@store');
        Route::post('/settings/advancedseo','Setting\SeoDetailController@update');
    });

    //password and avatar

    Route::get('/changepassword','UserProfileController@ChangePassword');
    Route::post('/changepassword','UserProfileController@updateChangePassword');

    Route::get('/changeavatar','UserProfileController@changeavatar');
    Route::post('/changeavatar','UserProfileController@updatechangeavatar');
    Route::get('/getavatar','UserProfileController@getavatar');


    Route::get('/editprofile','UserProfileController@edit');
    Route::get('/profile','UserProfileController@create');
    Route::post('/profile','UserProfileController@update');

    //Export/Import
    Route::group(['middleware' => ['permission:read-members']], function() {
        Route::get('/export', 'ExportMemberController@index');
    Route::get('/exportUsers', 'ExportMemberController@exportUsers');



    //import

    Route::get('/import', 'ImportMemberController@index');
    Route::post('/importUsers', 'ImportMemberController@importUsers');
        Route::get('/downloadformat', 'ImportMemberController@downloadFormat');
    });

    //Gallery
    Route::group(['middleware' => ['permission:read-gallery']], function() {
        //index
        Route::get('/gallery', 'GalleryController@index');

        //add
        Route::get('/gallery/create', 'GalleryController@create');
        Route::post('/gallery/store', 'GalleryController@store');

        //edit
        Route::get('/gallery/edit/{id}', 'GalleryController@edit');
        Route::post('/gallery/edit/{id}', 'GalleryController@update');

        //show
        Route::get('/gallery/{id}', 'GalleryController@show');
        Route::get('/gallery/details/{id}', 'GalleryController@showdetails');

        //delete
        Route::delete('/gallery/delete/{id}', 'GalleryController@destroy');

    //Gallery-photos

    Route::post('gallery/upload/photos/{gallery_id}','PhotosController@store');
    Route::get('gallery/view/photos/{gallery_id}', 'PhotosController@show');
    Route::get('gallery/display/photos/{gallery_id}', 'PhotosController@showdetails');
    Route::get('gallery/getphoto/{gallery_id}', 'PhotosController@getPhoto');
        Route::delete('/gallery/photo/delete/{id}', 'PhotosController@destroy');
    });

    //media file
    Route::group(['middleware' => ['permission:read-files']], function() {
        //index
        Route::get('/mediafile/list/{type}','MediaFilesController@list');
        Route::get('/mediafiles','MediaFilesController@index');

        //video
        Route::get('/mediafile/video/create','VideoController@create');
        Route::post('/mediafile/video/create','VideoController@store');
        Route::post('/mediafile/video/save','VideoController@storeVideo');

        //audio
        Route::get('/mediafile/audio/create','AudioController@create');
        Route::post('/mediafile/audio/create','AudioController@store');
        Route::post('/mediafile/audio/save','AudioController@storeAudio');

        Route::post('/media/storeaudios','AudioController@audiostore');

        //image
        Route::get('/mediafile/image/create','ImageController@create');
        Route::post('/mediafile/image/create','ImageController@store');
        Route::get('/mediafile/images','ImageController@listImages');
        Route::delete('/mediafile/image/delete/{id}','ImageController@destroy');

        //show
        Route::get('/mediafile/show/{id}','MediaFilesController@show');

        //delete
        Route::delete('/mediafile/delete/{id}','MediaFilesController@destroy');
    });

    //sermon
    Route::group(['middleware' => ['permission:read-sermons']], function() {
        Route::get('/sermons', 'SermonsController@index');
    Route::get('/sermon/show/{id}','SermonsController@show');
    Route::get('/download/sermon/{id}','SermonsController@download');
    });

    //Bulletins
    Route::group(['middleware' => ['permission:read-bulletins']], function() {
        //index
        Route::get('/bulletin/list/{type}','BulletinsController@list');
        Route::get('/bulletins','BulletinsController@index');

        //add
        Route::get('/bulletin/getDate','BulletinsController@getDate');
        Route::get('/bulletin/create','BulletinsController@create');
        Route::post('/bulletin/create','BulletinsController@store');

        //delete
        Route::delete('/bulletin/delete/{id}','BulletinsController@destroy');

        //download
        Route::get('/bulletin/download/{id}','BulletinsController@downloadattachments');
    });

    //Groups
    Route::group(['middleware' => ['permission:read-groups']], function() {
        //index
        Route::get('/groups','GroupsController@index');

        //add
        Route::get('/group/get','GroupsController@getData');
        Route::get('/group/create','GroupsController@create');
        Route::post('/group/create','GroupsController@store');

        //show
        Route::get('/group/show/{id}','GroupsController@show');

        //edit
        Route::get('/group/edit/{id}','GroupsController@edit');
        Route::post('/group/update/{id}','GroupsController@update');

        //delete
        Route::delete('/group/delete/{id}','GroupsController@destroy');

        //message
        Route::post('/group/sendMessage/{id}','GroupsController@message');
        Route::get('/group/messages/{id}','GroupsController@messageList');

    //grouplinks
        //index
        Route::get('/group/showMember/{id}','GroupLinksController@index');

        //add
        Route::get('/group/addMember/{group_id}','GroupLinksController@create');
        Route::post('/group/addMember/{group_id}','GroupLinksController@store');

        //edit
        Route::get('/group/editMember/{id}','GroupLinksController@edit');
        Route::post('/group/editMember/{id}','GroupLinksController@update');

        //delete
        Route::delete('/group/removeMember/{id}','GroupLinksController@destroy');
    });

    //reminder

    // Route::post('/reminder/events', 'ReminderController@events');

    //Quotes
    Route::group(['middleware' => ['permission:read-quotes']], function() {
        //index
        Route::get('/quote/list/{type}','QuotesController@list');
        Route::get('/quotes','QuotesController@index');
        Route::get('/quote/bible/{type}','QuotesController@books');
        Route::get('/quote/bible/{type}/{book}','QuotesController@bookByid');
        //add
        Route::get('/quote/add','QuotesController@create');
        Route::post('/quote/add','QuotesController@store');
        Route::post('/quote/validation','QuotesController@validation');

        //show
        Route::get('/quote/show/{id}', 'QuotesController@show');

        //edit
        Route::get('/quote/edit/list/{id}', 'QuotesController@editList');
        Route::get('/quote/edit/{id}', 'QuotesController@edit');
        Route::post('/quote/edit/{id}', 'QuotesController@update');
        Route::post('/quote/editValidation/{id}', 'QuotesController@editValidation');

        //reschedule
        Route::post('/quote/reschedule/{id}', 'QuotesController@reschedule');

        //delete
        Route::delete('/quote/delete/{id}', 'QuotesController@destroy');
    });

    //Funds
    Route::group(['middleware' => ['permission:read-funds']], function() {
        //index
        Route::get('/fund/list','FundController@list');
        Route::get('/funds','FundController@index');

        //add
        Route::get('/fund/showMember','FundController@searchMember');
        Route::get('/fund/add','FundController@create');
        Route::post('/fund/add','FundController@store');

        //show
        Route::get('/fund/show/{id}','FundController@fundDetails');

        //edit
        Route::get('/fund/editList/{id}','FundController@editList');
        Route::get('/fund/edit/{id}','FundController@edit');
        Route::post('/fund/edit/{id}','FundController@update');

        //delete
        Route::delete('/fund/delete/{id}','FundController@destroy');
    });

    //reports
    Route::group(['middleware' => ['permission:read-reports']], function() {
        Route::get('/reports','ReportsController@report');
    Route::get('/report/messageHistory/{subject}','ReportsController@messageHistory');
    //Route::get('/report/index','ReportsController@index');
    //Route::get('/report/show/{id}','ReportsController@show');
    //Route::post('/report/filter','ReportsController@create');
    Route::get('/report/birthday','ReportsController@exportBirthday');
    Route::get('/report/anniversary','ReportsController@exportAnniversary');
    Route::get('/report/activeMembers','ReportsController@exportActiveMembers');
    Route::get('/report/guestMembers','ReportsController@exportGuestMembers');
        Route::get('/report/suspendedMembers','ReportsController@exportSuspendedMembers');
    });

    //payment
    Route::group(['middleware' => ['permission:read-payments']], function() {
        Route::get('/payment/index/{id}','PaymentController@index');
        Route::post('/payment/response','PaymentController@response');
    });

    //prayer_requests
    Route::get('/prayerrequest/list/{status}','PrayerRequestsController@list');

    Route::get('/prayerrequests','PrayerRequestsController@index');

    Route::get('/prayerrequest/show/{id}','PrayerRequestsController@show');

    Route::get('/prayerrequest/responses/{id}','PrayerRequestsController@showResponse');

    Route::get('/prayerrequest/approveList/{id}','PrayerRequestsController@approveList');
    Route::get('/prayerrequest/approve/{id}','PrayerRequestsController@edit');
    Route::post('/prayerrequest/approve/{id}', 'PrayerRequestsController@update');

    Route::get('/prayerrequest/audio/store/{id}','PrayerRequestsController@createAudio');
    Route::post('/prayerrequest/audio/store/{id}', 'PrayerRequestsController@storeAudio');

    Route::get('/prayerrequest/create','PrayerRequestAddController@create');
    Route::post('/prayerrequest/create', 'PrayerRequestAddController@store');

    //helps
        //index
        Route::get('/help/list/{status}','HelpsController@list');
        Route::get('/helps','HelpsController@index');

        //add
        Route::get('/help/create','HelpAddController@create');
        Route::post('/help/create', 'HelpAddController@store');

        //show
        Route::get('/help/show/{id}','HelpsController@show');

        //edit
        Route::get('/help/showDetails/{id}','HelpsController@showDetails');
        Route::get('/help/edit/{id}','HelpsController@edit');
        Route::post('/help/update/{id}', 'HelpsController@update');

    //attendance
    Route::group(['middleware' => ['permission:read-members']], function() {
        Route::get('/meetings','AttendancesController@Create');
    Route::post('/meetings/importSummary','AttendancesController@store');
    Route::get('/meetings/downloadSummary','AttendancesController@index');


    Route::get('/event/{event_id}/downloadSummary','AttendancesController@summary');
    Route::get('/event/{event_id}/attendance','AttendancesController@createAttendance');
    Route::post('/event/{event_id}/attendance','AttendancesController@saveAttendance');
    });

    //botman (admin only)
    Route::group(['middleware' => ['adminonly']], function() {
        Route::get('/botman/index', 'BotmanMasterController@index');
        Route::get('/botman/create', 'BotmanMasterController@create');
        Route::post('/botman/create', 'BotmanMasterController@store');
        Route::get('/botman/{id}/edit', 'BotmanMasterController@edit');
        Route::post('/botman/{id}/update', 'BotmanMasterController@update');
        Route::delete('/botman/remove/{id}', 'BotmanMasterController@remove');
        Route::get('/botman/messages', 'BotmanMasterController@getMessages');
        Route::get('/botman/details/{id}/{type}', 'BotmanMasterController@getDetails');
    });

    //page (admin only)
    Route::group(['middleware' => ['adminonly']], function() {
        Route::get( '/pageCategory/list', 'PageCategoryController@list' );
    Route::post( '/pageCategory/add', 'PageCategoryController@store' );

    //page
    Route::get( '/page/list', 'PagesController@list' );
    Route::get( '/pages', 'PagesController@index' );

    Route::get( '/page/add', 'PagesController@create' );
    Route::post( '/page/add', 'PagesController@store' );
    Route::post( '/page/upload', 'PagesController@storeImage' );

    Route::get( '/page/showList/{id}', 'PagesController@showList' );
    Route::get( '/page/show/{id}', 'PagesController@show' );

    Route::get( '/page/editList/{id}', 'PagesController@editList' );
    Route::get( '/page/edit/{id}', 'PagesController@edit' );
    Route::post( '/page/edit/{id}', 'PagesController@update' );

    Route::delete( '/page/delete/{id}', 'PagesController@destroy' );

    Route::post( '/page/follow/{id}', 'PageDetailsController@follow' );
    Route::post( '/page/like/{id}', 'PageDetailsController@like' );
    Route::post( '/page/dislike/{id}', 'PageDetailsController@dislike' );

    //post
    Route::get( '/post/list', 'PostsController@indexList' );
    Route::get( '/posts', 'PostsController@index' );

    Route::get( '/post/showList/{id}', 'PostsController@showList' );
    Route::get( '/post/showImage/{id}', 'PostsController@imageList' );
    Route::get( '/post/show/{id}', 'PostsController@show' );

    Route::delete( '/post/delete/{id}', 'PostsController@destroy' );

    Route::get( '/post/add/list', 'PostAddController@createList' );
    Route::get( '/post/add', 'PostAddController@create' );
    Route::post( '/post/add', 'PostAddController@store' );
    Route::post( '/post/add/attachment', 'PostAddController@attachment' );

    Route::get( '/post/editList/{id}', 'PostEditController@editList' );
    Route::get( '/post/edit/{id}', 'PostEditController@edit' );
    Route::post( '/post/edit/{id}', 'PostEditController@update' );
    Route::post( '/post/edit/attachment/{id}', 'PostEditController@editAttachment' );

    Route::post( '/post/like/{post_id}', 'PostDetailController@like' );
    Route::post( '/post/dislike/{post_id}', 'PostDetailController@dislike' );
    Route::post( '/post/save/{post_id}', 'PostDetailController@save' );
    Route::post( '/post/unsave/{post_id}', 'PostDetailController@unsave' );

    Route::post( '/post/add/comment/{post_id}', 'PostCommentsController@addComment' );
    Route::get( '/post/edit/commentList/{comment_id}', 'PostCommentsController@editCommentList' );
    Route::post( '/post/edit/comment/{comment_id}', 'PostCommentsController@editComment' );
    Route::delete( '/post/delete/comment/{comment_id}', 'PostCommentsController@destroy' );

    Route::get( '/post/replyComment/{post_comment_id}', 'PostReplyCommentsController@list' );
    Route::post( '/post/reply/add/comment/{post_comment_id}', 'PostReplyCommentsController@addComment' );
    Route::post( '/post/reply/edit/comment/{post_comment_id}', 'PostReplyCommentsController@editComment' );
    Route::delete( '/post/reply/delete/comment/{post_comment_id}', 'PostReplyCommentsController@destroy' );

    Route::post( '/post/comment/like/{comment_id}', 'PostCommentDetailsController@like' );
        Route::post( '/post/comment/dislike/{comment_id}', 'PostCommentDetailsController@dislike' );

        Route::get( '/blogs', 'BlogsController@index' );
    });

    //feedbacks
        //index
        Route::get( '/feedbacks', 'FeedbackController@index' );

        //reply
        Route::get( '/feedback/edit/{feedbackid}', 'FeedbackController@edit' );
        Route::post( '/feedback/updateStatus/{id}', 'FeedbackController@updateStatus' );

    //index
    Route::get('/google-analytics', 'GoogleAnalyticsController@index');



    //Payaccounts
    Route::group(['middleware' => ['permission:read-payments']], function() {
       //index
        Route::get('/payaccount/list','Payment\PayaccountContorller@getlist');
        Route::get('/payaccounts','Payment\PayaccountContorller@index');

        //add
        Route::get('/payaccount/add/list','Payment\PayaccountContorller@addlist');
        Route::get('/payaccount/create','Payment\PayaccountContorller@create');
        Route::post('/payaccount/create','Payment\PayaccountContorller@store');

        //show
        Route::get('/payaccount/show/{id}','Payment\PayaccountContorller@show');

        //edit
        Route::get('/payaccount/editList/{id}','Payment\PayaccountContorller@editList');
        Route::get('/payaccount/edit/{id}','Payment\PayaccountContorller@edit');
        Route::post('/payaccount/update/{id}','Payment\PayaccountContorller@update');
        Route::post('/payaccount/status/{id}/update','Payment\PayaccountContorller@statusUpdate');

        //delete
        Route::delete('/payaccount/delete/{id}','Payment\PayaccountContorller@destroy');
    });


