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

        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect('/');
        }

        return view('events.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->with('error', 'Nincs jogosultságod eseményt létrehozni.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date_at' => 'required|date|after:now',
            'sale_start_at' => 'required|date|before:sale_end_at',
            'sale_end_at' => 'required|date|after:sale_start_at|before:event_date_at',
            'is_dynamic_price' => 'nullable|boolean',
            'max_number_allowed' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|max:2048',
        ], [
            'sale_start_at.before' => 'A kezdési dátumnak korábbinak kell lennie, mint a jegyeladás vége.',
            'sale_end_at.after' => 'A jegyeladás vége nem lehet korábban, mint a kezdete.',
            'sale_end_at.before' => 'A jegyeladás vége nem lehet az esemény után.',
            'event_date_at.after' => 'Az esemény dátumának a mai nap után kell lennie.',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('event_covers', 'public');
        }

        Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date_at' => $validated['event_date_at'],
            'sale_start_at' => $validated['sale_start_at'],
            'sale_end_at' => $validated['sale_end_at'],
            'is_dynamic_price' => $request->has('is_dynamic_price'),
            'max_number_allowed' => $validated['max_number_allowed'],
            'cover_image' => $validated['cover_image'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Az esemény sikeresen létrehozva!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->with('error', 'Nincs jogosultságod szerkeszteni.');
        }

        $event = Event::findOrFail($id);

        return view('events.edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date_at' => 'required|date|after:now',
            'sale_start_at' => 'required|date|before:sale_end_at',
            'sale_end_at' => 'required|date|after:sale_start_at|before:event_date_at',
            'is_dynamic_price' => 'nullable|boolean',
            'max_number_allowed' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('event_covers', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.edit', $event->id)->with('success', 'Az esemény sikeresen módosítva!');
    }

}
