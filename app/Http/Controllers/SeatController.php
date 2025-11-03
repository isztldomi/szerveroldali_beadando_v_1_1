<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect('/');
        }

        return view('seats.index');
    }
}
