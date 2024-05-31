<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Models\User;
use App\Services\SharingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SharingController extends Controller {
    private $service;
    public function __construct(SharingService $service)
    {
        $this->service = $service;
    }

    /**
     * Zobrazí pod uživateleme všechny jeho sdílení
     *
     * @return \Inertia\Response
     */
    public function index() {
        $user = auth()->user();
        $data = $this->service->index($user);

        return Inertia::render('subjects/sharedSubjects', $data);
    }
    /**
     * Slouží ke vytvoření sdílení
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $customMessages = [
            'users.required' => __('share.warning.required_user'),
            'permission.required' => __('share.warning.required_permission'),
        ];
        $validated = $request->validate([
            'users' => 'required',
            'permission' => 'required',
            'subject' => 'required',
        ], $customMessages);

        $data =  $this->service->store($validated);

        return redirect()->back()->with($data);
    }

    /**
     * Úprava sdílení
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $this->service->update($request->input('email'), $request->input('subject'), $request->input('permission'));
        return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => __('validation.custom.update')]);
    }

    /**
     * Odstranění sdílení
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($slug, User $user) {
        $this->service->delete($slug, $user);
        return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo smazáno']);
    }

    /**
     * Zobrazení všech nabídek ke sdílení předmětu
     *
     * @return \Inertia\Response
     */
    public function showOfferShare() {
        $subjects =  $this->service->showOfferShare(auth()->user());

        return Inertia::render('subjects/acceptSubject', compact('subjects'));
    }

    /**
     * Odmítnutí sdílení
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refuseShare(Request $request) {
        $subject = $this->service->refuseShare(auth()->user(), $request->slug);

        if ($subject->created_by == auth()->user()->id) {
            return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo zrušeno']);
        } else {
            return to_route('subject.index')->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo zrušeno']);
        }
    }
    /**
     * Přijmutí sdílení
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptShare(Request $request) {
        $this->service->acceptShare($request->slug);

        return redirect()->back();
    }
}
