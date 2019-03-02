<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class CurrencyController
 * @package App\Http\Controllers
 */
class CurrencyController extends Controller
{

    /**
     * Response all currencies from dictionary
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrencies(Request $request)
    {
        $currencyModel = Currency::get();

        $currencyArray = $currencyModel->toArray();

        if(!empty($currencyArray)) {
            return Response::json(['status' => 200, 'data' => $currencyArray])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 500, 'message' => 'Error, currencies can not be found'])->setStatusCode(500);
        }
    }

    /**
     * Response information about currency by itself code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrency(Request $request)
    {
        $currencyCode = (int) $request->input('currencyCode');

        $currencyEntity = Currency::whereNumCode($currencyCode)->first();

        if(!empty($currencyEntity)) {
            return Response::json(['status' => 200, 'data' => $currencyEntity])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Error, currency can not be found'])->setStatusCode(400);
        }
    }

}
