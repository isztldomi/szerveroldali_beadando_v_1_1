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
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect('/');
        }

        $totalEvents = Event::count();

        $totalTickets = Ticket::count();

        $totalIncome = Ticket::sum('price');

        // 3 legnépszerűbb ülőhely (seat_number) és eladott jegyek száma
        $topSeats = Ticket::select('seat_id', DB::raw('COUNT(*) as sold_count'))
            ->groupBy('seat_id')
            ->orderByDesc('sold_count')
            ->limit(3)
            ->with('seat:id,seat_number') // betölti a seat_number-t
            ->get();


        // Legfrissebb események, 5 per oldal
        $events = DB::table('events')
            ->select('id', 'title')
            ->orderByDesc('event_date_at', 'desc')
            ->paginate(5);

        $total_seats = DB::table('seats')->count();

        foreach ($events as $event) {
            // eladott jegyek száma
            $event->sold_tickets_count = DB::table('tickets')
                ->where('event_id', $event->id)
                ->count();

            // szabad jegyek
            $event->available_tickets = $total_seats - $event->sold_tickets_count;

            // bevétel
            $event->revenue = DB::table('tickets')
                ->where('event_id', $event->id)
                ->sum('price');
        }

        return view('dashboard', [
            'totalEvents' => $totalEvents,
            'totalTickets' => $totalTickets,
            'totalIncome' =>$totalIncome,
            'topSeats' => $topSeats,
            'events' => $events,
            'totalSeats' => $total_seats,
        ]);
    }
}
