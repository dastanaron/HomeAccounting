<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FundsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getFunds(Request $request)
    {

    }

    public function createFund(Request $request)
    {

    }

    public function setFund(Request $request)
    {

    }

    public function deleteFund(Request $request)
    {

    }

}
