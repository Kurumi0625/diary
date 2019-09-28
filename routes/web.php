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
    return redirect('admin/diary/');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('diary/create', 'Admin\DiaryController@add');
    Route::post('diary/create', 'Admin\DiaryController@create');
    Route::get('diary', 'Admin\DiaryController@index');
    Route::get('diary/edit', 'Admin\DiaryController@edit');
    Route::post('diary/edit', 'Admin\DiaryController@update');
    Route::get('diary/delete', 'Admin\DiaryController@delete');
    Route::get('diary/contents', 'Admin\DiaryController@show');
    Route::post('diary/getDiaries', 'Admin\DiaryController@getDiaries');
});