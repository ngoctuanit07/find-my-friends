<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'v1'), function()
{
    Route::controller('me', 'MeController');
    Route::controller('login', 'LoginController');
    Route::controller('friend', 'FriendController');
});
