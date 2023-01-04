<?php

namespace App\Http\Controllers\CRUDControllers;

use App\Http;
use App\Components\PA\CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class CategoriesController
 * @package App\Http\Controllers\CRUDControllers
 */
class CategoriesController extends Http\Controllers\Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(Request $request)
    {
        $categoriesCRUD = new CRUD\Categories($request);
        return Response::json($categoriesCRUD->getList())->setStatusCode(200);
    }

    public function getById(Request $request)
    {
        $categoriesCRUD = new CRUD\Categories($request);
        return Response::json($categoriesCRUD->getById())->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCategory(Request $request)
    {
        $categoriesCRUD = new CRUD\Categories($request);

        if($categoriesCRUD->create() === true) {
            return Response::json(['status' => 200, 'message' => 'Category created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not created'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCategory(Request $request)
    {
        $categoriesCRUD = new CRUD\Categories($request);

        if($categoriesCRUD->update() === true) {
            return Response::json(['status' => 200, 'message' => 'Category is updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not updated'])->setStatusCode(400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteCategory(Request $request)
    {
        $categoriesCRUD = new CRUD\Categories($request);

        if($categoriesCRUD->delete() === true) {
            return Response::json(['status' => 200, 'message' => 'Category is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not deleted'])->setStatusCode(400);
        }
    }
}
