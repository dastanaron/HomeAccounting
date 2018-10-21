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

    public function vkUnAthorize(Request $request)
    {
        if(!empty($request->input('uid'))) {

            $socialNetwork = SocialNetwork::where('social_id', '=', $request->input('uid'))->first();

            $socialNetwork->social_id = $request->input('uid');
            $socialNetwork->social_network = 'deleted';
            $saved = $socialNetwork->save() ? 'true' : 'false';

            return ['status' => 'ok', 'message' => 'Saved data social_network is '.$saved];

        }
        return ['status' => 'error', 'message' => 'Error deleted data'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function pushOn(Request $request)
    {
        $user = \Auth::user();
        $token = $request->input('browser_token');
        $userId = $user->id;
        $userName = $user->name;

        $socialNetwork = new SocialNetwork();
        $socialNetwork->social_id = $token;
        $socialNetwork->social_network = 'web-push';
        $socialNetwork->user_id = $userId;
        $socialNetwork->first_name = $userName;
        $saved = $socialNetwork->save() ? 'true' : 'false';

        return ['status' => 'ok', 'message' => 'Saved data social_network is '.$saved];
    }

    /**
     * Web-push token delete
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function pushOff(Request $request)
    {
        $user = \Auth::user();

        $socialNetworkDelete = SocialNetwork::where('social_network', '=', 'web-push')
            ->where('user_id', '=', $user->id)
            ->delete();

        return ['status' => 'ok', 'message' => 'Deleted data social_network is '.$socialNetworkDelete];
    }

}
