<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('info', 'infoController@info')->name('info');
Route::get('/verify/{token}','Auth\RegisterController@verify')->name('register.verify');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/','HomeController@index')->name('home');
        Route::resource('users','UsersController');
    }
);

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('profile', 'UserController@profile')->name('profile');
    //  Route::post('profile', 'UserController@withValidator');
        Route::post('profile', 'UserController@update_avatar');
       // Route::post('profile', 'UserController@profile')->name('changePassword');
        Route::patch('profile', 'UserController@update');
    }
);