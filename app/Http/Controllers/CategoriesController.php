<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\helpers\CategoriesHelper;
use Illuminate\Support\Facades\Response;

class CategoriesController extends Controller
{
    public function getCategories(Request $request)
    {
        return (new CategoriesHelper($request))->getCategories();
    }

    public function createCategory(Request $request)
    {
        $categoryHelper = new CategoriesHelper($request);

        if($categoryHelper->createCategory() === true) {
            return Response::json(['status' => 200, 'message' => 'Category created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not created'])->setStatusCode(400);
        }
    }

    public function setCategory(Request $request)
    {
        $categoryHelper = new CategoriesHelper($request);

        if($categoryHelper->setCategory() === true) {
            return Response::json(['status' => 200, 'message' => 'Category is updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not updated'])->setStatusCode(400);
        }
    }

    public function deleteCategory(Request $request)
    {
        $categoryHelper = new CategoriesHelper($request);

        if($categoryHelper->deleteCategory() === true) {
            return Response::json(['status' => 200, 'message' => 'Category is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Category is not deleted'])->setStatusCode(400);
        }
    }
}
