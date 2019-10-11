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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('/', 'HomeController@index')->name('home');
Route::get('/resource', 'HomeController@resource')->name('resource');
Route::post('/breakdown','HomeController@addBreakdown')->name('breakdown');
Route::post('/edit/breakdown','HomeController@addBreakdown')->name('edit.breakdown');
Route::post('/ready','HomeController@addReady')->name('ready');
