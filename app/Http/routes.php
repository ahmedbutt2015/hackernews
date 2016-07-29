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

use Illuminate\Support\Facades\Auth;

Route::group(['middleware' => 'auth'],function(){
    Route::get('/', 'FeedController@feedPage');
    Route::any('/submit', 'FeedController@submit');
    Route::any('/vote', 'VoteController@vote');
    Route::post('/addComment', 'BlogController@addComment');
    Route::post('/addReply', 'BlogController@addReply');
    Route::delete('/deleteBlog', 'BlogController@deleteBlog');
    Route::delete('/deleteComment', 'BlogController@deleteComment');
    Route::get('/blog/{id}', 'BlogController@blog');

    Route::get('/logout', function(){
        Auth::logout();
        return redirect('/');
    });
});

Route::group(['middleware' => 'guest'],function(){
    Route::any('/register','AuthController@register');
    Route::any('/login','AuthController@login');
    Route::get('/user/verify/{token}', 'AuthController@verifyUser');
});

