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

// Middleware
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('jwtx.auth')->post('users', function(Request $request) {
    return auth()->user();
});

Route::middleware('jwtx.auth')->post('movie/showPlaylist', 'PlaylistController@getPlaylist');

Route::post('movie/search', 'SearchController@searchByKeyword');
Route::post('movie/findById', 'SearchController@findById');
Route::post('movie/addToPlaylist', 'PlaylistController@addToPlaylist');

Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');