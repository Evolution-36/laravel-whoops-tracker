<?php

/*
 * --------------------------------------------------------------------------
 * General routes
 * --------------------------------------------------------------------------
 * Defines all the routes that can be accessed by everyone
 * Routes for authentication
 */

Route::group(['prefix' => 'lwt'], function() {
    Route::get('/', 'Evolution36\WhoopsTracker\Controllers\WhoopsController@index');
});