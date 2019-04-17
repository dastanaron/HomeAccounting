<?php

namespace App\Http\Controllers;


class PrivateAreaController extends Controller
{
    public function index()
    {
        return view('funds/pa-index');
    }
}
