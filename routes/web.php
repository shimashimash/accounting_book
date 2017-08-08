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
    Route::get('/months/{date?}', 'MonthsController@index');
    Route::get('/months/edit/{date}', 'MonthsController@edit');
    Route::put('/months/edit/{month_id}', 'MonthsController@update');
    Route::get('/months/detail/{month_id}', 'MonthsController@detail');

    // 日の画面
    Route::get('/days/{date}', 'DaysController@index');
    Route::post('/days/{id}', 'DaysController@edit');
});
