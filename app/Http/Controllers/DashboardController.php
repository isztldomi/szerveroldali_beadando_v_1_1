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
        $topSeats = Ticket::select('seat_number', DB::raw('COUNT(*) as sold_count'))
            ->groupBy('seat_number')
            ->orderByDesc('sold_count')
            ->limit(3)
            ->get();


        // Legfrissebb események, 5 per oldal
        $events = Event::withCount(['tickets as sold_tickets_count' => function ($query) {
            $query->where('sold', true); // vagy ahol eladott a logika
        }])
        ->withCount('tickets as total_tickets_count')
        ->paginate(5);

        // Eseményekhez számoljuk a bevételt
        foreach ($events as $event) {
            // eladott jegyek bevétele
            $event->revenue = Ticket::where('event_id', $event->id)
                                    ->where('sold', true)
                                    ->sum('price');

            // szabad jegyek
            $event->available_tickets = $event->total_tickets_count - $event->sold_tickets_count;
        }

        return view('dashboard', [
            'totalEvents' => $totalEvents,
            'totalTickets' => $totalTickets,
            'totalIncome' =>$totalIncome,
            'topSeats' => $topSeats,
            'events '=> $events
        ]);
    }
}
