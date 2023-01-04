<?php

namespace App\Http\Controllers\CRUDControllers;

use App\Http;
use App\Components\PA\CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class BillsController
 * @package App\Http\Controllers\CRUDControllers
 */
class BillsController extends Http\Controllers\Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBills(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        return Response::json($billsCRUD->getList())->setStatusCode(200);
    }

    public function getById(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        return Response::json($billsCRUD->getById())->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBill(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        if($billsCRUD->create() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not created'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBill(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        if($billsCRUD->update() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill saved'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not saved'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteBill(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        if($billsCRUD->delete() === true) {
            return Response::json(['status' => 200, 'message' => 'Bill is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Bill is not deleted'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function MoneyTransaction(Request $request)
    {
        $billsCRUD = new CRUD\Bills($request);

        if($billsCRUD->MoneyTransaction() === true) {
            return Response::json(['status' => 200, 'message' => 'Transaction is corrected'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Transaction is invalid'])->setStatusCode(400);
        }
    }
}
