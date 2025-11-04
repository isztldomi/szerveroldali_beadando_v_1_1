<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Ticket;

class SeatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect('/');
        }

        $seats = Seat::orderBy('seat_number', 'asc')->paginate(10);

        foreach ($seats as $seat) {
            $seat->deletable = Ticket::where('seat_id', $seat->id)->count() === 0;
        }

        return view('seats.index', [
            'seats' => $seats,
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/');
        }

        $seat = Seat::find($id);

        if (!$seat) {
            return redirect()->route('seats.index')->withErrors('A kiválasztott szék nem létezik.');
        }

        $validated = $request->validate([
            'seat_number' => 'required|string|max:10|unique:seats,seat_number,' . $seat->id,
            'base_price' => 'required|numeric|min:0',
        ], [
            'seat_number.required' => 'A szék számát kötelező megadni.',
            'seat_number.unique' => 'Ez a szék szám már létezik.',
            'seat_number.string' => 'A szék számának szövegnek kell lennie.',
            'seat_number.max' => 'A szék száma legfeljebb 10 karakter lehet.',
            'base_price.required' => 'Az ár megadása kötelező.',
            'base_price.numeric' => 'Az árnak számnak kell lennie.',
            'base_price.min' => 'Az ár nem lehet negatív.',
        ]);

        $seat->update($validated);

        return redirect()->back()->with('success', 'A szék adatai sikeresen frissítve!');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->withErrors('Nincs jogosultságod székek törléséhez.');
        }

        $seat = Seat::find($id);
        if (!$seat) {
            return redirect()->route('seats.index')->withErrors('A kiválasztott szék nem létezik.');
        }

        $ticketCount = Ticket::where('seat_id', $seat->id)->count();
        if ($ticketCount > 0) {
            return redirect()->route('seats.index')->withErrors('A szék nem törölhető, mert már lefoglalták.');
        }

        $seat->delete();

        return redirect()->route('seats.index')->with('success', 'A szék sikeresen törölve.');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('/')->withErrors('Nincs jogosultságod eseményt létrehozni.');
        }


        return redirect()->route('seats.index')->with('success', 'Az esemény sikeresen létrehozva!');
    }
}
