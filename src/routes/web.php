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
Route::any('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('/', function (){
    $breakdown = \App\Log::whereNull('ready')->with('unit')->get();
    $ready = \App\Log::whereNotNull('ready')->where('ready','>', now()->subHours(12))->with('unit')->get();

    return view('welcome')->with([
        'breakdown' => $breakdown,
        'ready' => $ready,
    ]);
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/resource', 'HomeController@resource')->name('resource');
Route::get('/download', 'HomeController@download')->name('download');
Route::post('/breakdown','HomeController@addBreakdown')->name('breakdown');
Route::post('/edit/breakdown','HomeController@editBreakdown')->name('edit.breakdown');
Route::post('/ready','HomeController@addReady')->name('ready');
