<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $eventIds = $user->tickets()->pluck('event_id')->unique()->toArray();

        $events = Event::whereIn('id', $eventIds)->orderBy('event_date_at', 'desc')->get();

        foreach ($events as $event) {
            $event->userTickets = $user->tickets()
                ->where('event_id', $event->id)
                ->with('seat')
                ->get()
                ->sortBy(function ($ticket) {
                    return $ticket->seat->seat_number;
                })
                ->map(function ($ticket) {
                    $barcode = new DNS1D();
                    $barcode->setStorPath(storage_path('framework/barcodes/'));

                    $ticket->barcodeImage = $barcode->getBarcodePNG($ticket->barcode, 'C128');

                    return $ticket;
                });
        }

        return view('tickets.index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $now = now();

        if ($now->lt($event->sale_start_at)) {
            return redirect()
                ->route('events.show', $event->id)
                ->withErrors('A jegyvásárlás még nem kezdődött el.');
        }

        if ($now->gt($event->sale_end_at)) {
            return redirect()
                ->route('events.show', $event->id)
                ->withErrors('A jegyvásárlás már lezárult.');
        }

        $availableSeats = $event->remainingSeats();

        $user = auth()->user();
        $userTicketsCount = $user ? $user->tickets()->where('event_id', $event->id)->count() : 0;
        $remainingTicketsCount = $event->max_number_allowed - $userTicketsCount;

        if ($event->is_dynamic_price) {

            foreach ($availableSeats as $seat) {
                $seat->dynamic_price = $this->calculateDynamicPrice($event, $seat->id);
            }
        } else {
            foreach ($availableSeats as $seat) {
                $seat->dynamic_price = $seat->base_price;
            }
        }

        return view('tickets.create', [
            'event' => $event,
            'availableSeats' => $availableSeats,
            'remainingTicketsCount'  => $remainingTicketsCount,
            'maxTicketsCount'   =>  $event->max_number_allowed,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['exists:seats,id'],
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = auth()->user();
        $now = now();

        if ($now->lt($event->sale_start_at)) {
            return back()
                ->withErrors(['general' => 'A jegyvásárlás még nem kezdődött el.'])
                ->withInput();
        }

        if ($now->gt($event->sale_end_at)) {
            return back()
                ->withErrors(['general' => 'A jegyvásárlás már lezárult.'])
                ->withInput();
        }

        $userTicketsCount = $user->tickets()->where('event_id', $event->id)->count();
        $remainingTicketsCount = $event->max_number_allowed - $userTicketsCount;

        if (count($request->seat_ids) > $remainingTicketsCount) {
            return back()->withErrors([
                'seat_ids' => "Maximum $remainingTicketsCount jegyet vásárolhatsz ezen az eseményen."
            ])->withInput();
        }

        $takenSeatIds = Ticket::where('event_id', $event->id)
            ->whereIn('seat_id', $request->seat_ids)
            ->pluck('seat_id')
            ->toArray();

        if (!empty($takenSeatIds)) {
            return back()->withErrors([
                'seat_ids' => 'A következő helyek már foglaltak: ' . implode(', ', $takenSeatIds)
            ])->withInput();
        }

        foreach ($request->seat_ids as $seatId) {
            Ticket::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'seat_id' => $seatId,
                'price' => $event->is_dynamic_price
                    ? $this->calculateDynamicPrice($event, $seatId)
                    : Seat::findOrFail($seatId)->base_price,
                'barcode' => $this->generateBarcode(),
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Jegyek sikeresen lefoglalva!');
    }

    protected function calculateDynamicPrice(Event $event, $seatId)
    {
        $seat = Seat::findOrFail($seatId);

        $basePrice = $seat->base_price;

        $daysUntil = Carbon::now()->diffInDays(Carbon::parse($event->event_date_at), false);
        $daysUntil = max($daysUntil, 0);

        $totalSeats = Seat::count();
        $bookedSeats = Ticket::where('event_id', $event->id)->count();

        $occupancy = $totalSeats > 0 ? $bookedSeats / $totalSeats : 0;

        $price = $basePrice * (1 - 0.5 * (1 / ($daysUntil + 1))) * (1 + 0.5 * $occupancy);

        return round($price);
    }

    protected function generateBarcode()
    {
        do {
            $barcode = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);

            $exists = Ticket::where('barcode', $barcode)->exists();

        } while ($exists);

        return $barcode;
    }

    public function admission()
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->withErrors('Nincs jogosultságod székek törléséhez.');
        }

        return view('tickets.admission');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
