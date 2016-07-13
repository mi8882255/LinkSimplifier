<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/bookmark/add', 'ApiController@addNew');
Route::post('/api/bookmark/get10', 'ApiController@get10');
Route::post('/api/bookmark/getbyid', 'ApiController@getByIdwComments');
Route::post('/api/comments/add', 'ApiController@addComment');
Route::post('/api/comments/modify', 'ApiController@modifyComment');
Route::post('/api/comments/delete', 'ApiController@deleteComment');
