<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerifyEmailController extends Controller
{
    /**
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        return Inertia::render('VerifyEmail');
    }

    /**
     * Zpracování ověření
     * @param EmailVerificationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return to_route('dashboard')->with([
            'message' => __('email.emailVerify.verified'),
            'status' => \App\Enums\ToastifyStatus::SUCCESS
        ]);
    }

    /**
     * Odeslání ověřovacího emailu
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestVerification()
    {
        $user = Auth()->user();
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        return response()->json(['message' => __('email.emailVerify.notice')]);
    }
}
