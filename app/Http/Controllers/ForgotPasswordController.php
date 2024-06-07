<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ForgotPasswordController extends Controller
{
    public function passwordResetShow()
    {
        return Inertia::render('register', ['value' => 2]);
    }

    public function passwordResetStore(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        activity()->causedBy(User::where('email',$request->only('email'))->first())->log('Žádost o resetování hesla');

        return $status === Password::RESET_LINK_SENT
            ? redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Email o resetování hesla Vám byl zaslán!'])
            : redirect()->back()->with(['status' => ToastifyStatus::ERROR]);
    }

    public function passwordReset(ForgotPasswordRequest $request)
    {
        $token = $request->token;
        $passwordReset = DB::table('password_resets')->get();
        $passwordResetEntry = $passwordReset->first(function($reset) use ($token) {
            return Hash::check($token, $reset->token);
        });
        if(!$passwordResetEntry) {
            return redirect()->back()->with(['status' => ToastifyStatus::ERROR, 'message' => 'Neplatný token!']);
        }

        $credentials = $request->only('password', 'password_confirmation', 'token');
        $credentials['email'] = $passwordResetEntry->email;

        $status = Password::reset(
            $credentials,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        Activity()->causedBy(User::where('email', $credentials['email'])->first())->log('Úspěšně resetované heslo');
        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Heslo bylo úspěšně resetováno!'])
            : back()->with(['status' => ToastifyStatus::ERROR, 'message' => 'Nastala chyba!']);
    }
}
