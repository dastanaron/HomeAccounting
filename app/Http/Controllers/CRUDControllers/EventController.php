<?php

namespace App\Http\Controllers\CRUDControllers;

use App\Http;
use App\Components\PA\CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EventController extends Http\Controllers\Controller
{
    public function getEvents(Request $request)
    {
        $eventCRUD = new CRUD\Events($request);
        return Response::json($eventCRUD->getList())->setStatusCode(200);
    }

    public function createEvent(Request $request)
    {
        $eventCRUD = new CRUD\Events($request);

        if($eventCRUD->create() === true) {
            return Response::json(['status' => 200, 'message' => 'Event created success'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not created'])->setStatusCode(400);
        }
    }

    public function setEvent(Request $request)
    {
        $eventCRUD = new CRUD\Events($request);

        if($eventCRUD->update() === true) {
            return Response::json(['status' => 200, 'message' => 'Event is updated'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not updated'])->setStatusCode(400);
        }
    }

    public function deleteEvent(Request $request)
    {
        $eventCRUD = new CRUD\Events($request);

        if($eventCRUD->delete() === true) {
            return Response::json(['status' => 200, 'message' => 'Event is deleted'])->setStatusCode(200);
        }
        else {
            return Response::json(['status' => 400, 'message' => 'Event is not deleted'])->setStatusCode(400);
        }
    }
}
