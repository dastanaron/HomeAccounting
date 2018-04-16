<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallBackController extends Controller
{

    public function vk(Request $request)
    {
        dump($request->json());
    }

}
