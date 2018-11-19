<?php

use Illuminate\Http\Request;

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

// Group JWT-Auth routes
Route::middleware(['jwtx.auth'])->group(function () {

    // Show signed user
    Route::get('user/getInfo', 'UserController@getInfo');

    // User playlist
    Route::get('movie/showPlaylist', 'PlaylistController@getPlaylist');
    Route::post('movie/addToPlaylist', 'PlaylistController@addToPlaylist');
    Route::post('movie/setWatched', 'PlaylistController@setWatched');
    Route::post('movie/findById', 'SearchController@findById');
});

Route::post('email/send', 'EmailController@sendEmail');

// Search from OMDb
Route::post('movie/search', 'SearchController@searchByKeyword');

// Playlist functionality
Route::get('movie/getToplist', 'PlaylistController@getToplist');

// User authentication
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');