<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        // itt tartok
        $user = auth()->user();

        if (!$user->isAdmin()) {
            // Ha nem admin, vissza a főoldalra
            return redirect('/');
        }
        // Összes esemény
        $totalEvents = Event::count();

        // Összes eladott jegy
        $totalTickets = Ticket::count();

        // Összes bevétel
        $totalIncome = Ticket::sum('price');

        // 3 legnépszerűbb ülőhely (seat_number) és eladott jegyek száma
        $topSeats = Ticket::select('seat_id', DB::raw('COUNT(*) as sold_count'))
            ->groupBy('seat_id')
            ->orderByDesc('sold_count')
            ->limit(3)
            ->with('seat:id,seat_number') // betölti a seat_number-t
            ->get();


        // Legfrissebb események, 5 per oldal
        $events = Event::with('tickets')->withCount('seats')->latest()->paginate(5);

foreach ($events as $event) {
    $event->sold_tickets_count = $event->tickets->count(); // eladott jegyek
    $event->available_tickets = $event->seats_count - $event->sold_tickets_count; // szabad jegyek
    $event->revenue = $event->tickets->sum('price'); // bevétel
}


        return view('dashboard', [
            'totalEvents' => $totalEvents,
            'totalTickets' => $totalTickets,
            'totalIncome' =>$totalIncome,
            'topSeats' => $topSeats,
            'events' => $events
        ]);
    }
}
