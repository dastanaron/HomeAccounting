<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Callback Routes
|--------------------------------------------------------------------------
|
|
*/

Route::any('/telegram', function () {

    $input = file_get_contents('php://input');

    //TODO: realized callback functional

    return 'ok';
});