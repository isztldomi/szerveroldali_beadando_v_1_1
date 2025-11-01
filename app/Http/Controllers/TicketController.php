<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Seat;

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
        //
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
