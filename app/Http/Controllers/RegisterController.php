<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Events\SendEmailWelcome;
use App\Http\Requests\RegisterRequest;
use App\Models\Settings;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class RegisterController extends Controller {
    public function create() {
        return Inertia::render('Authentication/Authentication', ['value' => 0]);
    }

    /**
     * Vytvoření nového uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request, VerifyEmailController $controller) {
        $restricted = Settings::find(1);
        if ($restricted === true) {
            return redirect()->back()->with(['status' => ToastifyStatus::ERROR])->withErrors(['msg' => __('authentication.restricted')]);
        }
        $usr = $request->only(['firstname', 'email', 'lastname', 'password']);
        $usr['type_id'] = $request->type['value'];
        $usr['slug'] = SlugService::createSlug(User::class, 'slug', $request->firstname);
        $user = User::create($usr);
        auth()->login($user);
        activity()
            ->causedBy($user)
            ->log('Registrace uživatele');
        $controller->requestVerification();
        event(new SendEmailWelcome(auth()->user()));
        return to_route('verification.notice');
    }
}
