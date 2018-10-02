<?php

/*
 * --------------------------------------------------------------------------
 * General routes
 * --------------------------------------------------------------------------
 * Defines all the routes that can be accessed by everyone
 * Routes for authentication
 */

Route::group(['prefix' => 'lwt', 'middleware' => ['web']], function () {
    Route::get('/', 'Evolution36\WhoopsTracker\Controllers\WhoopsController@viewer');

    Route::get('/whoopses', 'Evolution36\WhoopsTracker\Controllers\WhoopsController@index');
    Route::get('/whoops/{id}', 'Evolution36\WhoopsTracker\Controllers\WhoopsController@show');

    Route::get('/occurrence/{id}', 'Evolution36\WhoopsTracker\Controllers\OccurrenceController@show');
});
