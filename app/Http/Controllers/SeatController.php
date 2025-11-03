<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;

class SeatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect('/');
        }

        $seats = Seat::orderBy('seat_number', 'asc')->paginate(10);

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

        $seat = Seat::findOrFail($id);

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
}
