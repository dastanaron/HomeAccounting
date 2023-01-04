<?php

namespace App\Http\Controllers\CRUDControllers;

use App\Http;
use App\Components\PA\CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class FundsController
 * @package App\Http\Controllers\CRUDControllers
 */
class FundsController extends Http\Controllers\Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getFunds(Request $request)
    {
        $fundsCRUD = new CRUD\Funds($request);
        return Response::json($fundsCRUD->getList())->setStatusCode(200);
    }

    public function getById(Request $request) {
        $fundsCRUD = new CRUD\Funds($request);
        return Response::json($fundsCRUD->getById())->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFund(Request $request)
    {
        $fundsCRUD = new CRUD\Funds($request);

        if($fundsCRUD->create() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund cannot be created'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setFund(Request $request)
    {
        $fundsCRUD = new CRUD\Funds($request);

        if($fundsCRUD->update() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund has been updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund cannot be saved'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFund(Request $request)
    {
        $fundsCRUD = new CRUD\Funds($request);

        if($fundsCRUD->delete() === true) {
            return Response::json(['status' => 200, 'message' => 'Fund was deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Fund was not deleted'])->setStatusCode(400);
        }
    }

}
