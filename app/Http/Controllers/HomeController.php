<?php

namespace App\Http\Controllers;

use App\SocialNetwork;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = \Auth::user()->id;

        $socialNetwork = SocialNetwork::where('user_id', '=', $user_id)->first();

        return view('home', ['socialNetwork' => $socialNetwork]);
        //return \Response::view('home', ['socialNetwork' => $socialNetwork]);
    }
}
