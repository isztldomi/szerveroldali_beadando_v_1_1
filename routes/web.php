<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\DashboardController;



Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    // Jegyvásárló oldal
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/index', [TicketController::class, 'index'])->name('tickets.index');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/dashboard/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/dashboard/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/dashboard/events/update/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/dashboard/events/delete/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/dashboard/seats', [SeatController::class, 'index'])->name('seats.index');
    Route::put('/dashboard/seats/update/{id}', [SeatController::class, 'update'])->name('seats.update');
    Route::delete('/dashboard/seats/delete/{id}', [SeatController::class, 'destroy'])->name('seats.destroy');
    Route::get('/dashboard/tickets/admission', [TicketController::class, 'admission'])->name('tickets.admission');



    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__.'/auth.php';
