<?php

use Illuminate\Http\Request;

Route::get('/settings/nalog-ru', 'NalogRuSettingController@getSettings');
Route::post('/settings/nalog-ru/register', 'NalogRuSettingController@register');
Route::post('/settings/nalog-ru/restore-password', 'NalogRuSettingController@restorePassword');
Route::post('/settings/nalog-ru/create', 'NalogRuSettingController@createIntegration');
Route::put('/settings/nalog-ru/update', 'NalogRuSettingController@update');