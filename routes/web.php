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

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::group(['middleware' => ['web']], function () {
    // 月の画面
    Route::get('/home/{year?}/{month?}', 'HomeController@index')->where('year', '[0-9]+');
    Route::get('/home/{year}/{month}/edit', 'HomeController@edit');
    Route::put('/home/{monthId}/edit', 'HomeController@update');
    Route::post('/home', 'HomeController@create');
    Route::get('/home/detail/{monthId}', 'HomeController@detail');
    Route::post('/home/validation', 'HomeController@validation');

    // 日の画面
    Route::get('/add/{year}/{month}/{day}', 'AddController@index');
    Route::post('/add/{id}', 'AddController@edit');
});
