<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallBackController extends Controller
{

    public function vk(Request $request)
    {
        $data = $request->json()->all();

        $vkId = $data['object']['user_id'];

        $message = $data['object']['body'];

        return 'ok';

    }

}
