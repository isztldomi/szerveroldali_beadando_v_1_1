<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('dashboard');
    }
}
