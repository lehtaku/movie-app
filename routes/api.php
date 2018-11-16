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
    Route::post('user', function(Request $request) {
        return auth()->user();
    });

    // User playlist
    Route::post('movie/showPlaylist', 'PlaylistController@getPlaylist');
    Route::post('movie/addToPlaylist', 'PlaylistController@addToPlaylist');
});

// Search from OMDb
Route::get('movie/search', 'SearchController@searchByKeyword');
Route::get('movie/findById', 'SearchController@findById');

// Playlist functionality
Route::get('movie/getMostPopular', 'SearchController@PlaylistController@getMostPopular');

// User authentication
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');