<?php

namespace App\Http\helpers;

use App\Events;
use Illuminate\Http\Request;
use Auth;

class EventHelper
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $user_id;

    /**
     * BillsHelper constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user_id = Auth::user()->id;
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function createEvent()
    {
        $event = new Events();

        $event->user_id = $this->user_id;

        $event->type_event = $this->request->input('type_event');
        $event->head = $this->request->input('head');
        $event->message = $this->request->input('message');
        $event->completed = !empty($this->request->input('completed')) ? $this->request->input('completed') : 0;
        $event->date_notif = $this->request->input('date');

        return $event->save();
    }

    public function setEvent()
    {
        $eventId = $this->request->input('event_id');
        $event = Events::whereUserId($this->user_id)->where('id', '=', $eventId)->first();

        if(empty($event)) {
            return false;
        }

        $event->type_event = $this->request->input('type_event');
        $event->head = $this->request->input('head');
        $event->message = $this->request->input('message');
        $event->completed = !empty($this->request->input('completed')) ? $this->request->input('completed') : 0;
        $event->date_notif = $this->request->input('date');

        return $event->save();
    }

    public function deleteEvent()
    {
        $eventId = $this->request->input('event_id');
        $event = Events::whereUserId($this->user_id)->where('id', '=', $eventId)->first();

        if(empty($event)) {
            return false;
        }

        return $event->delete();
    }
}