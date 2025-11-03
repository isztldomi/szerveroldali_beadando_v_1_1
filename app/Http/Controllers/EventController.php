<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('event_date_at', '>', now())
            ->orderBy('event_date_at', 'desc')
            ->paginate(5);

        return view('events.index', [
            'events' => $events,
        ]);
    }

    public function show(Event $event)
    {
        return view('events.show', [
            'event' => $event,
        ]);
    }

    public function create()
    {
        return view('events.create');
    }
}
