<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //return user info
    public function view() {
        $user = User::with('roles')->find(auth()->user()->id);
        return Inertia::render('user', compact('user'));
    }
}
