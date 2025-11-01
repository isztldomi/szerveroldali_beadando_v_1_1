<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;



Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::resource('tickets', TicketController::class);
#Route::get('/', function () {
#    return view('welcome');
#});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Jegyvásárló oldal
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');

    // Jegy mentése
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
