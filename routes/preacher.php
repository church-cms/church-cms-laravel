<?php

 Route::get('/dashboard', 'DashboardController@index');

    //notification

    Route::get('/notification/list', 'NotificationController@indexList');
    Route::get('/notifications', 'NotificationController@index');
    Route::post('/notification/read', 'NotificationController@store');
    Route::get('/notification/showList', 'NotificationController@showList');

    //sermons
    Route::get('/sermon', 'SermonsController@index');
    Route::get('/sermon/create', 'SermonsController@create');
    Route::get('/sermon/edit/{id}', 'SermonsController@edit');
    Route::post('/sermon/edit/{id}', 'SermonsController@update');
    Route::post('/sermon/save', 'SermonsController@store');
    Route::delete('/sermon/delete/{id}', 'SermonsController@destroy');

    //sermon_links
    Route::get('/links/{sermons_id}','SermonLinkController@create');
    Route::post('/links/{sermons_id}','SermonLinkController@store');
    Route::get('/download/{id}','SermonLinkController@getDownload');
    Route::post('/links/update/{id}', 'SermonLinkController@update');
    Route::post('/links/validateedit/{id}', 'SermonLinkController@validateedit');
    Route::get('/links/edit/{id}', 'SermonLinkController@edit');
    Route::delete('/links/delete/{id}', 'SermonLinkController@destroy');


    Route::get('/edit/list/{name}','PreacherController@create');
    Route::get('/edit/{name}','PreacherController@edit');
    Route::post('/edit/{name}','PreacherController@update');

     Route::get('/video-conference', 'VideoConferencesController@index');
     Route::get('/video-conference/{slug}', 'VideoConferencesController@show');

    //preacher
    //Route::get('/preacher', 'PreacherController@index');
    //Route::get('/add','PreacherController@store');

    //activity_log

    Route::get('/activity','ActivityLogController@index');
