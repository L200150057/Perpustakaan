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
Route::get('/autocomplete', 'SearchController@autocomplete');
Route::post('/excel', 'DataController@create_excel');

Route::resource('user', 'UserController');
Route::post('/user/{id}/update_password', 'UserController@update_password')->name('update_password');
