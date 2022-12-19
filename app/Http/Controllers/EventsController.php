<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function getAllEvents() {
        $events = Event::query()->join('event_types', function ($join) {
            $join->on('events.type_id', '=', 'event_types.id');
        })->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path']);

        return json_encode($events);
    }

    public function getLatestEvents() {
        $events = Event::query()->orderBy('date', 'ASC')
            ->where('date','>=', date('y-m-d'))->limit(6)->get();

        return json_encode($events);
    }

    public function mostAttendedEvents() {
        $events=Event::query()->withCount('tickets')->orderBy('tickets_count', 'desc')
            ->limit(3)->get();

        return json_encode($events);
    }

    public function getSpecificEvent($id) {
        $event = Event::query()->where('id', '=', $id)->get();

        return json_encode($event);
    }

    public function getUpcomingEvents() {
        $events = Event::query()->join('event_types', function ($join) {
            $join->on('events.type_id', '=', 'event_types.id');
        })->whereDate('date', '>', date('Y-m-d'))
            ->orderBy('date')
            ->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path']);

        return json_encode($events);
    }

    public function getPastEvents() {
        $events = Event::query()->join('event_types', function ($join) {
            $join->on('events.type_id', '=', 'event_types.id');
        })->whereDate('date', '<=', date('Y-m-d'))
            ->orderBy('date')
            ->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path']);

        return json_encode($events);
    }

    public function getEventByType($type) {
        $type = ucwords(str_replace('-', ' ', $type));

        $events = Event::query()->join('event_types', function ($join) use ($type) {
            $join->on('events.type_id', '=', 'event_types.id')
                ->where('event_types.type', '=', $type);
        })->get();

        return json_encode($events);
    }

    public function groupEventsByType() {
        $groups = Event::query()->join('event_types', function ($join) {
            $join->on('events.type_id', '=', 'event_types.id');
        })
            ->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path'])
            ->groupBy('type');

        return json_encode($groups);
    }

    public function groupUpcomingEventsByType($type) {
        $type = ucwords(str_replace('-', ' ', $type));

        $events = Event::query()->join('event_types', function ($join) use ($type) {
            $join->on('events.type_id', '=', 'event_types.id')
                ->where('event_types.type', '=', $type);
        })
            ->whereDate('date', '>', date('Y-m-d'))
            ->orderBy('date')
            ->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path']);

        return json_encode($events);
    }

    public function groupPastEventsByType($type) {
        $type = ucwords(str_replace('-', ' ', $type));

        $events = Event::query()->join('event_types', function ($join) use ($type) {
            $join->on('events.type_id', '=', 'event_types.id')
                ->where('event_types.type', '=', $type);
        })
            ->whereDate('date', '<=', date('Y-m-d'))
            ->orderBy('date')
            ->get(['events.id', 'name', 'organizer', 'description', 'date', 'time', 'type', 'image_path', 'carousel_image_path']);

        return json_encode($events);
    }
}
