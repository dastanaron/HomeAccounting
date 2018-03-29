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
Route::get('/pa/bills-list', 'BillsController@getBills');

Route::post('/pa/bills', 'BillsController@createBill');
Route::put('/pa/bills', 'BillsController@setBill');
Route::delete('/pa/bills', 'BillsController@deleteBill');

/**
 * Funds methods
 */
Route::get('/pa/funds-list', 'FundsController@getFunds');

Route::post('/pa/funds', 'FundsController@createFund');
Route::put('/pa/funds', 'FundsController@setFund');
Route::delete('/pa/funds', 'FundsController@deleteFund');

/**
 * Categories methods
 */
Route::get('/pa/categories-list', 'CategoriesController@getCategories');

Route::post('/pa/categories', 'CategoriesController@createCategory');
Route::put('/pa/categories', 'CategoriesController@setCategory');
Route::delete('/pa/categories', 'CategoriesController@deleteCategory');