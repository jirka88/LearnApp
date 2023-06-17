<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //return user info
    public function view() {
        $id = auth()->user()->id;
        $usr =  User::with('roles')->find($id);
        return Inertia::render('user', compact('usr'));
    }
}
