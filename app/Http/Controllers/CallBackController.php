<?php

namespace App\Http\Controllers;

use App\SocialNetwork;
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

    public function vkAuthorize(Request $request)
    {
        if(!empty($request->input('uid'))) {

            $socialNetwork = SocialNetwork::getOrCreateObject($request->input('uid'));

            $socialNetwork->social_id = $request->input('uid');
            $socialNetwork->social_network = 'vk';
            $socialNetwork->user_id = \Auth::user()->id;
            $socialNetwork->first_name = $request->input('first_name');
            $socialNetwork->last_name = $request->input('last_name');
            $socialNetwork->photo = $request->input('photo');
            $saved = $socialNetwork->save() ? 'true' : 'false'; //Строчная передача в сообщение с сервера

            return ['status' => 'ok', 'message' => 'Saved data social_network is '.$saved];

        }
        return ['status' => 'error', 'message' => 'Error recording data'];
    }

}
