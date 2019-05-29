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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('create', 'ScheduleController@create');

Route::post('create', 'ScheduleController@store');

//予定の詳細表示
Route::get('/schedules/{scheduleId}', 'ScheduleController@show');

//予定の編集
Route::get('/edit/{scheduleId}', 'EditController@edit');

//編集した予定の更新
Route::patch('edit/{scheduleId}', 'EditController@update');

//予定の削除
Route::delete('delete/{scheduleId}', 'EditController@destroy');

//出欠情報の編集
Route::get('/attend/{scheduleId}/user/{userId}', 'AttendController@attend');

//出欠情報の更新
Route::patch('attend/{scheduleId}/user/{userId}', 'AttendController@update');