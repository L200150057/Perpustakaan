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

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('data', 'DataController');
Route::get('/', 'SearchController@search')->name('search');

Route::post('/import_excel', 'ExcelController@import_excel');
Route::post('/export_excel', 'ExcelController@export_excel')->name('export_excel');
Route::get('/export', 'ExcelController@export')->name('export');

Route::resource('user', 'UserController');
Route::post('/user/{id}/update_password', 'UserController@update_password')->name('update_password');
