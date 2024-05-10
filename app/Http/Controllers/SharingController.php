<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Services\SharingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SharingController extends Controller {
    /**
     * Zobrazí pod uživateleme všechny jeho sdílení
     *
     * @return \Inertia\Response
     */
    public function index(SharingService $service) {
        $user = auth()->user();
        $data = $service->index($user);

        return Inertia::render('subjects/sharedSubjects', $data);
    }

    /**
     * Zobrazení všech nabídek ke sdílení předmětu
     *
     * @return \Inertia\Response
     */
    public function showOfferShare(SharingService $service) {
        $subjects = $service->showOfferShare(auth()->user());

        return Inertia::render('subjects/acceptSubject', compact('subjects'));
    }

    /**
     * Odmítnutí sdílení
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refuseShare(Request $request, SharingService $service) {
        $subject = $service->refuseShare(auth()->user(), $request->slug);

        if ($subject->created_by == auth()->user()->id) {
            return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo zrušeno']);
        } else {
            return to_route('subject.index')->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo zrušeno']);
        }
    }
}
