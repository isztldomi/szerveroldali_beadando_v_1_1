<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $availableSeats = $event->remainingSeats();

        $user = auth()->user();
        $userTicketsCount = $user ? $user->tickets()->where('event_id', $event->id)->count() : 0;
        $remainingTicketsCount = $event->max_number_allowed - $userTicketsCount;

        if ($event->is_dynamic_price) {
            $totalSeats = \App\Models\Seat::count();
            $occupiedSeats = $event->tickets()->count();
            $occupancy = $totalSeats > 0 ? $occupiedSeats / $totalSeats : 0;

            $daysUntil = max(0, now()->diffInDays($event->event_date_at));

            foreach ($availableSeats as $seat) {
                $basePrice = $seat->base_price;

                // Dinamikus ár a megadott képlet szerint
                $seat->dynamic_price = round(
                    $basePrice * (1 - 0.5 * (1 / ($daysUntil + 1))) * (1 + 0.5 * $occupancy)
                );
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

        // mennyi jegyet vásárolhat még a felhasználó az adott eseményre
        $userTicketsCount = $user->tickets()->where('event_id', $event->id)->count();
        $remainingTicketsCount = $event->max_number_allowed - $userTicketsCount;

        // ne vásároljon többet, mint a megengedett
        if (count($request->seat_ids) > $remainingTicketsCount) {
            return back()->withErrors([
                'seat_ids' => "Maximum $remainingTicketsCount jegyet vásárolhatsz ezen az eseményen."
            ])->withInput();
        }

        // az ülőhelyek szabadok-e
        $takenSeatIds = Ticket::where('event_id', $event->id)
            ->whereIn('seat_id', $request->seat_ids)
            ->pluck('seat_id')
            ->toArray();

        if (!empty($takenSeatIds)) {
            return back()->withErrors([
                'seat_ids' => 'A következő helyek már foglaltak: ' . implode(', ', $takenSeatIds)
            ])->withInput();
        }

        // Ha minden rendben, létrehozzuk a jegyeket
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

        return redirect()->route('tickets.my')->with('success', 'Jegyek sikeresen lefoglalva!');
    }

    // Segédfüggvény dinamikus árhoz
    protected function calculateDynamicPrice(Event $event, $seatId)
    {
        $seat = Seat::findOrFail($seatId);
        // Példa: dinamikus ár = alapár + eseményhez kötött extra
        return $seat->base_price + rand(0, 1000);
    }

    // Segédfüggvény vonalkód generálásához
    protected function generateBarcode()
    {
        return str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);
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
