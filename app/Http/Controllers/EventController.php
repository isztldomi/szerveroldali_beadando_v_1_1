<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;

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

        $total = Seat::count();

        $remaining = $event->remainingSeatsCount();

        return view('events.show', [
            'event' => $event,
            'total' => $total,
            'remaining' => $remaining,
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
            'title.required' => 'A cím megadása kötelező.',
            'title.string' => 'A cím csak szöveg lehet.',
            'title.max' => 'A cím legfeljebb 255 karakter hosszú lehet.',
            'description.required' => 'A leírás megadása kötelező.',
            'description.string' => 'A leírás csak szöveg lehet.',
            'event_date_at.required' => 'Az esemény dátumát kötelező megadni.',
            'event_date_at.date' => 'Az esemény dátuma érvényes dátum kell legyen.',
            'event_date_at.after' => 'Az esemény dátumának a mai nap után kell lennie.',
            'sale_start_at.required' => 'A jegyeladás kezdési dátumát kötelező megadni.',
            'sale_start_at.date' => 'A jegyeladás kezdete érvényes dátum kell legyen.',
            'sale_start_at.before' => 'A kezdési dátumnak korábbinak kell lennie, mint a jegyeladás vége.',
            'sale_end_at.required' => 'A jegyeladás záró dátumát kötelező megadni.',
            'sale_end_at.date' => 'A jegyeladás vége érvényes dátum kell legyen.',
            'sale_end_at.after' => 'A jegyeladás vége nem lehet korábban, mint a kezdete.',
            'sale_end_at.before' => 'A jegyeladás vége nem lehet az esemény után.',
            'is_dynamic_price.boolean' => 'A dinamikus árképzés mező csak igaz/hamis értéket vehet fel.',
            'max_number_allowed.required' => 'A maximálisan vásárolható jegyek számát kötelező megadni.',
            'max_number_allowed.integer' => 'A maximálisan vásárolható jegyek száma csak egész szám lehet.',
            'max_number_allowed.min' => 'Legalább 1 jegyet engedélyezni kell.',
            'cover_image.image' => 'A borítóképnek érvényes képformátumúnak kell lennie (pl. JPG, PNG).',
            'cover_image.max' => 'A borítókép mérete nem haladhatja meg a 2 MB-ot.',
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

        $isSaleStarted = now()->greaterThanOrEqualTo($event->sale_start_at);

        return view('events.edit', [
            'event' => $event,
            'isSaleStarted' => $isSaleStarted,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $isSaleStarted = now()->greaterThanOrEqualTo($event->sale_start_at);

        if ($isSaleStarted) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'cover_image' => 'nullable|image|max:2048',
            ], [
                'title.required' => 'A cím megadása kötelező.',
                'title.string' => 'A cím csak szöveg lehet.',
                'title.max' => 'A cím legfeljebb 255 karakter lehet.',
                'description.required' => 'A leírás megadása kötelező.',
                'description.string' => 'A leírás csak szöveg lehet.',
                'cover_image.image' => 'A borítóképnek kép típusúnak kell lennie.',
                'cover_image.max' => 'A borítókép mérete nem haladhatja meg a 2 MB-ot.',
            ]);
        } else {
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
                'title.required' => 'A cím megadása kötelező.',
                'title.string' => 'A cím csak szöveg lehet.',
                'title.max' => 'A cím legfeljebb 255 karakter lehet.',
                'description.required' => 'A leírás megadása kötelező.',
                'description.string' => 'A leírás csak szöveg lehet.',
                'event_date_at.required' => 'Az esemény dátumát kötelező megadni.',
                'event_date_at.date' => 'Az esemény dátuma érvényes dátum kell legyen.',
                'event_date_at.after' => 'Az esemény dátumának a mai nap után kell lennie.',
                'sale_start_at.required' => 'A jegyeladás kezdő dátumát kötelező megadni.',
                'sale_start_at.date' => 'A jegyeladás kezdete érvényes dátum kell legyen.',
                'sale_start_at.before' => 'A jegyeladás kezdete nem lehet később, mint a vége.',
                'sale_end_at.required' => 'A jegyeladás záró dátumát kötelező megadni.',
                'sale_end_at.date' => 'A jegyeladás vége érvényes dátum kell legyen.',
                'sale_end_at.after' => 'A jegyeladás vége nem lehet korábban, mint a kezdete.',
                'sale_end_at.before' => 'A jegyeladás vége nem lehet az esemény után.',
                'is_dynamic_price.boolean' => 'A dinamikus árképzés mező csak igaz/hamis értéket kaphat.',
                'max_number_allowed.required' => 'A maximálisan vásárolható jegyek számát kötelező megadni.',
                'max_number_allowed.integer' => 'A maximálisan vásárolható jegyek száma csak egész szám lehet.',
                'max_number_allowed.min' => 'Legalább 1 jegyet engedélyezni kell.',
                'cover_image.image' => 'A borítóképnek kép típusúnak kell lennie.',
                'cover_image.max' => 'A borítókép mérete nem haladhatja meg a 2 MB-ot.',
            ]);
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('event_covers', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.edit', $event->id)->with('success', 'Az esemény sikeresen módosítva!');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->with('error', 'Nincs jogosultságod események törléséhez.');
        }

        $event = Event::findOrFail($id);

        $soldTickets = Ticket::where('event_id', $event->id)->count();

        if ($soldTickets > 0) {
            return back()->with('error', 'Ez az esemény nem törölhető, mert már vásároltak rá jegyeket.');
        }

        if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Az esemény sikeresen törölve.');
    }
}
