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

Route::get('/api/bookmark/add', 'ApiController@addNew');
Route::get('/api/bookmark/get10', 'ApiController@get10');
Route::get('/api/bookmark/getbyid', 'ApiController@getByIdwComments');
Route::get('/api/comments/add', 'ApiController@addComment');
Route::get('/api/comments/modify', 'ApiController@modifyComment');
Route::get('/api/comments/delete', 'ApiController@deleteComment');
