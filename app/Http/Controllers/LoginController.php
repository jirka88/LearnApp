<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function edit() {
        return Inertia::render('register', ['value' => 1]);
    }

    /**
     * Přihlášení uživatele
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request) {
        $this->checkTooManyFailedAttempts();
        $credentials = $request->only('email', 'password');
        $isActive = User::where('email', $credentials['email'])->first();
        if($isActive == null) {
            return redirect()->back()->with(['status' => ToastifyStatus::ERROR])->withErrors(['msg' => __('auth.exist')]);
        }
        if($isActive['active']) {
            if(!Auth::validate($credentials)) {
                RateLimiter::hit($this->throttleKey(),120);
                return redirect()->back()->with(['status' => ToastifyStatus::ERROR])->withErrors(['msg' => __('auth.failed')]);
            }
            RateLimiter::clear($this->throttleKey());

            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            Auth::login($user, $request->get('remember'));
            return redirect()->intended("/dashboard");
        }
        else {
            return redirect()->back()->with(['status' => ToastifyStatus::ERROR])->withErrors(['msg' => __('auth.activate')]);
        }

    }
    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }
    public function checkTooManyFailedAttempts()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages(['msg' => trans('auth.throttle',
            ['seconds' => $seconds,
            'minutes' => ceil($seconds / 60)
            ]),
        ]);
    }
    public function passwordReset() {
        return Inertia::render('register', ['value' => 2]);
    }

}
