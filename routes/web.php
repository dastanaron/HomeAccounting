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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



/**
 * Personal Area routes
 */

Route::get('/pa', 'PrivateAreaController@index');

/**
 * Bills methods
 */
Route::get('/pa/get-bills', 'PrivateAreaController@getBills');
Route::post('/pa/create-bills', 'PrivateAreaController@createBill');
Route::put('/pa/update-bills', 'PrivateAreaController@setBill');