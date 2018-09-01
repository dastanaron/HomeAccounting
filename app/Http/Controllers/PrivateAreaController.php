<?php

namespace App\Http\Controllers;


class PrivateAreaController extends Controller
{
    /**
     * PrivateAreaController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('funds/pa-index');
    }


}
