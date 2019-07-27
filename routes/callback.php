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

    $tempFile = storage_path('logs/telegram') . 'callback.log';

    file_put_contents($tempFile, $input, FILE_APPEND);

    return 'ok';
});