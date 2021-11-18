<?php

use Illuminate\Support\Facades\Route;

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
// AUTH CONTROLLER //
Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::post('login', 'AuthController@login');

    Route::post('recover_password', 'AuthController@passwordRecovery');
    Route::post('reset_password', 'AuthController@resetPassword');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change_password', 'AuthController@resetPassword');
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('impersonate/{id}', 'AuthController@impersonate');
        Route::patch('update_profile', 'AuthController@updateProfile');
    });
});
