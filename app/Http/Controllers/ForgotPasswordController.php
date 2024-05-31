<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function passwordResetShow() {
        return Inertia::render('register', ['value' => 2]);
    }
    public function passwordResetStore(Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Email o resetování hesla Vám byl zaslán!'])
            : redirect()->back()->with(['status' => ToastifyStatus::ERROR]);
    }
    public function passwordReset(ForgotPasswordRequest $request) {
            $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Heslo bylo úspěšně resetováno!'])
            : back()->with(['status' => ToastifyStatus::ERROR, 'message' => 'Nastala chyba!']);
    }
}
