<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
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

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__.'/auth.php';
