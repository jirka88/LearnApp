<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

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
        Activity()->causedBy($request->user())->log('Ověření emailové adresy');
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
