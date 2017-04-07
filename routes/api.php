<?php
URL::forceSchema('https');
Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {
    Route::post('tutors/search', 'TutorsController@search');
    Route::get('tutors/reviews/{id}', 'TutorsController@reviews');

    Route::resource('requests', 'RequestsController', ['only' => 'store']);

    Route::resource('cv', 'CvController', ['only' => 'store']);
    Route::resource('stream', 'StreamController', ['only' => 'store']);

    Route::resource('sms', 'SmsController');
});
