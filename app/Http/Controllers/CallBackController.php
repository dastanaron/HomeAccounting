<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;

class CallBackController extends Controller
{
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

        $socialNetwork = new Models\SocialNetwork();
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
        $userId = \Auth::user();

        $socialNetworkDelete = Models\SocialNetwork::where('social_network', '=', 'web-push')
            ->where('user_id', '=', $userId)
            ->delete();

        return ['status' => 'ok', 'message' => 'Deleted data social_network is '.$socialNetworkDelete];
    }

}
