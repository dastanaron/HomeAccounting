<?php

namespace App\Http\Controllers;

use App\Http\helpers\EventHelper;
use Illuminate\Http\Request;
use App\Events;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getEvents(Request $request)
    {
        $user_id = \Auth::user()->id;
        return Events::whereUserId($user_id)->get();
    }


    public function createEvent(Request $request)
    {
        $eventHelper = new EventHelper($request);

        if($eventHelper->createEvent() === true) {
            return Response::json(['status' => 200, 'message' => 'Event created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not created'])->setStatusCode(400);
        }
    }

    public function setEvent(Request $request)
    {
        $eventHelper = new EventHelper($request);

        if($eventHelper->setEvent() === true) {
            return Response::json(['status' => 200, 'message' => 'Event is updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not updated'])->setStatusCode(400);
        }
    }

    public function deleteEvent(Request $request)
    {
        $eventHelper = new EventHelper($request);

        if($eventHelper->deleteEvent() === true) {
            return Response::json(['status' => 200, 'message' => 'Event is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not deleted'])->setStatusCode(400);
        }
    }
}
