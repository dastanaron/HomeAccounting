<?php

namespace App\Http\Controllers;

use App\Bills;
use App\Http\helpers\BillsHelper;
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
        return view('funds/pa-index');
    }

    public function getBills()
    {
        $userId = \Auth::user()->id;
        return Bills::whereUserId( $userId )->get();
    }

    public function createBill(Request $request)
    {
        $billsHelper = new BillsHelper($request);
        $billsHelper->createBill();
    }

    public function setBill(Request $request)
    {
        $billsHelper = new BillsHelper($request);
        $billsHelper->setBill();
    }


}
