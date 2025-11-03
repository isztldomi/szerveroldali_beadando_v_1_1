<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;



Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

#Route::get('/', function () {
#    return view('welcome');
#});




Route::middleware('auth')->group(function () {
    // Jegyvásárló oldal
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // Megvásárolt jegyeim
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');

    // Jegy mentése
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/events/create', [EventController::class, 'create'])->name('events.create');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__.'/auth.php';
