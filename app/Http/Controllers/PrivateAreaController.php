<?php

namespace App\Http\Controllers;

use App\Bills;
use Illuminate\Http\Request;

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
        return 'success';
    }

    public function getBills()
    {
        return Bills::where('user_id', 'in', 1)->get();
    }
    public function setBill()
    {
        return null;
    }

}
