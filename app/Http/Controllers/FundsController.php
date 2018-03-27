<?php

namespace App\Http\Controllers;

use App\Http\helpers\FundsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FundsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function getFunds(Request $request)
    {
        $fundsHelper = new FundsHelper($request);
        return $fundsHelper->getFunds();
    }

    public function createFund(Request $request)
    {
        $fundsHelper = new FundsHelper($request);

        if($fundsHelper->createFunds() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund is not created'])->setStatusCode(400);
        }

    }

    public function setFund(Request $request)
    {
        $fundsHelper = new FundsHelper($request);

        if($fundsHelper->setFunds() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund is updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund is not saved'])->setStatusCode(400);
        }
    }

    public function deleteFund(Request $request)
    {
        $fundsHelper = new FundsHelper($request);

        if($fundsHelper->deleteFunds() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund is not deleted'])->setStatusCode(400);
        }
    }

}
