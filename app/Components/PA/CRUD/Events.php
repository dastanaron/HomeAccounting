<?php

declare(strict_types=1);

namespace App\Components\PA\CRUD;

use App\Library\BaseInterfaces;
use App\Library\CRUD;
use App\Models;
use Illuminate\Database\Eloquent;

/**
 * Class Event
 * @package App\Components\PA\CRUD
 */
class Events extends CRUD\AbstractCUDWithRelatedUser implements BaseInterfaces\CollectionList
{

    public function getList() : Eloquent\Collection
    {
        return Models\Events::whereUserId($this->userId)->get();
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        $event = new Models\Events();

        $event->user_id = $this->userId;

        $event->type_event = $this->request->input('type_event');
        $event->head = $this->request->input('head');
        $event->message = $this->request->input('message');
        $event->completed = !empty($this->request->input('completed')) ? $this->request->input('completed') : 0;
        $event->date_notif = $this->request->input('date');

        return (bool) $event->save();
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        $eventId = $this->request->input('event_id');
        $event = Models\Events::whereUserId($this->userId)->where('id', '=', $eventId)->first();

        if(empty($event)) {
            return false;
        }

        $event->type_event = $this->request->input('type_event');
        $event->head = $this->request->input('head');
        $event->message = $this->request->input('message');
        $event->completed = !empty($this->request->input('completed')) ? $this->request->input('completed') : 0;
        $event->date_notif = $this->request->input('date');

        return (bool) $event->save();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete() : bool
    {
        $eventId = $this->request->input('event_id');
        $event = Models\Events::whereUserId($this->userId)->where('id', '=', $eventId)->first();

        if(empty($event)) {
            return false;
        }

        return (bool) $event->delete();
    }
}