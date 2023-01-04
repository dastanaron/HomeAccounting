<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Bills methods
 */
Route::middleware(['auth:api'])->namespace('CRUDControllers')->group(function () {
    Route::get('/pa/bills-list', 'BillsController@getBills');
    Route::get('/pa/bills/{id}', 'BillsController@getById');

    Route::post('/pa/bills', 'BillsController@createBill');
    Route::put('/pa/bills', 'BillsController@setBill');
    Route::delete('/pa/bills', 'BillsController@deleteBill');

    Route::post('/pa/bills/transfer', 'BillsController@MoneyTransaction');
});

/**
 * Funds methods
 */
Route::middleware(['auth:api'])->namespace('CRUDControllers')->group(function () {
    Route::get('/pa/funds-list', 'FundsController@getFunds');
    Route::get('/pa/funds/{id}', 'FundsController@getById');

    Route::post('/pa/funds', 'FundsController@createFund');
    Route::put('/pa/funds', 'FundsController@setFund');
    Route::delete('/pa/funds', 'FundsController@deleteFund');
});

/**
 * Categories methods
 */
Route::middleware(['auth:api'])->namespace('CRUDControllers')->group(function () {
    Route::get('/pa/categories-list', 'CategoriesController@getCategories');
    Route::get('/pa/categories/{id}', 'CategoriesController@getById');

    Route::post('/pa/categories', 'CategoriesController@createCategory');
    Route::put('/pa/categories', 'CategoriesController@setCategory');
    Route::delete('/pa/categories', 'CategoriesController@deleteCategory');
});

Route::any('/qr-code-scanner/send-check', 'QrCodeScannerController@sendCheck');
