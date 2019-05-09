<?php

namespace App\Http\Controllers;

use App\Models;
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

        $cacheKey = md5('allCurrency');

        $currencyModel = \Cache::get($cacheKey);

        $fromCache = true;

        if($currencyModel === null) {
            $currencyModel = Models\Currency::get();
            \Cache::add($cacheKey, $currencyModel, 10);
            $fromCache = false;
        }

        $currencyArray = $currencyModel->toArray();

        if(!empty($currencyArray)) {
            return Response::json(['status' => 200, 'data' => $currencyArray, 'fromCache' => $fromCache])->setStatusCode(200);
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

        $cacheKey = md5($currencyCode);

        $currencyEntity = \Cache::get($cacheKey);

        $fromCache = true;

        if($currencyEntity === null) {
            $currencyEntity = Models\Currency::whereNumCode($currencyCode)->first();
            \Cache::add($cacheKey, $currencyEntity, 10);
            $fromCache = false;
        }

        if(!empty($currencyEntity)) {
            return Response::json(['status' => 200, 'data' => $currencyEntity, 'fromCache' => $fromCache])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Error, currency can not be found'])->setStatusCode(400);
        }
    }

}
