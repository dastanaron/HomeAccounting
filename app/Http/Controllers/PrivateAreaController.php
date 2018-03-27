<?php

namespace App\Http\Controllers;

use App\Bills;
use App\Http\helpers\BillsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        if($billsHelper->createBill() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not created'])->setStatusCode(400);
        }
    }

    public function setBill(Request $request)
    {
        $billsHelper = new BillsHelper($request);
        if($billsHelper->setBill() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill saved'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not saved'])->setStatusCode(400);
        }
    }

    public function deleteBill(Request $request)
    {
        $billsHelper = new BillsHelper($request);

        if($billsHelper->deleteBill() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not deleted'])->setStatusCode(400);
        }
    }


}
