<?php

use Illuminate\Http\Request;
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

Route::post('auth/register', 'Api\Auth\RegisterController@action')->name('register');

Route::post('auth/login', 'Api\Auth\LoginController')->name('login');

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'auth', 'namespace' => 'Api\Auth'], function () {
    //Route::post('/logout', 'LogoutController');
    Route::get('/me', 'MeController');
});




/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
