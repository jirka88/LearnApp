<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create() {
        return Inertia::render('register', ['value' => 0]);
    }

    /**
     * Vytvoření nového uživatele
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request)
    {
        $usr = $request->only(['firstname', 'email', 'password']);
        $usr['type_id'] = $request->type['value'];
        $usr['slug'] = SlugService::createSlug(User::class, 'slug', $request->firstname);
        $user = User::create($usr);
        auth()->login($user);
        return redirect()->intended("/dashboard");
    }
}
