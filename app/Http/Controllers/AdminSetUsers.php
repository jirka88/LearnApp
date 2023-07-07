<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSetUsers extends Controller
{
    public function index() {
        $users = User::orderBy('role_id', 'ASC')->orderby('firstname', 'ASC')->with(['roles', 'accountTypes'])->get();
        return Inertia::render('allUsers', compact('users'));
    }
    public function getUserSubjects(User $user) {
        $subjects = User::with('patritions')->find($user->id);
        return Inertia::render('subjects_admin_view', compact('subjects'));
    }
}
