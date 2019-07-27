<?php

namespace App\Http\Controllers;

use Illuminate\Http;

class SettingsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings/index');
    }
}
