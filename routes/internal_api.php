<?php

use Illuminate\Http\Request;

Route::get('/settings/nalog-ru', 'NalogRuSettingController@getSettings');
Route::post('/settings/nalog-ru/create', 'NalogRuSettingController@createIntegration');