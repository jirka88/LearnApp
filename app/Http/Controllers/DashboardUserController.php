<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Enums\UserRoles;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Licences;
use App\Models\Roles;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardUserController extends Controller {
    protected $userModel;
    private $dashboardService;
    private $userService;

    public function __construct(User $user, DashboardService $dashboardService, UserService $userService) {
        $this->userModel = $user;
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
    }

    /**
     * Vrátí informace o uživateli
     *
     * @return \Inertia\Response
     */
    public function view(): \Inertia\Response
    {
        $data = $this->userService->getUserInfo(auth()->user());

        return Inertia::render('Profile/Profile', $data);
    }

    /**
     * Aktualizace uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $updateRequest): \Illuminate\Http\RedirectResponse
    {
        $this->userService->update(auth()->user(), $updateRequest);

        return redirect()->back()->with(['message' =>  __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Resetování hesla
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordReset(PasswordResetRequest $passwordResetRequest): \Illuminate\Http\RedirectResponse
    {
        if (!Hash::check($passwordResetRequest->oldPassword, auth()->user()->password)) {
            return back()->withErrors(['oldPassword' => 'Staré heslo se liší!']);
        }
        if ($passwordResetRequest->oldPassword == $passwordResetRequest->newPassword) {
            return back()->withErrors(['newPasswordSameAsOld' => 'Nové heslo nesmí být stejné jako staré!']);
        }
        auth()->user()->update([
            'password' => $passwordResetRequest->newPassword,
        ]);

        return redirect()->back()->with(['message' => 'Heslo bylo úspěšně změněno!', 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Nastavení možnosti přijímání sdílení od uživatelů
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeShare(Request $request) {
        $customMessages = [
            'share.required' => 'Je nutné zadat sdílení',
        ];
        $validated = $request->validate([
            'share' => 'required',
        ], $customMessages);
        auth()->user()->update([
            'canShare' => $validated['share']['id'],
        ]);

        return redirect()->back()->with(['message' => 'Sdílení bylo změněno!', 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Redirect k formuláři k nahlášení chyby
     *
     * @return \Inertia\Response
     */
    public function report() {
        return inertia::render('Report');
    }

    /**
     * Získá základní uživatelské statistiky
     *
     * @return \Inertia\Response
     */
    public function getUserStats() {
        $stats = $this->dashboardService->index($this->userModel, auth()->user()->role_id);

        return Inertia::render('Dashboard/Dashboard', compact('stats'));
    }

    /**
     * Změní profilovou fotku uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeProfilePicture(Request $request) {
        $customMessages = [
            'savedImage.required' => 'Obrázek není nahrán!',
        ];
        $request->validate([
            'savedImage' => 'required',
        ], $customMessages);

        if (Auth()->user()->image) {
            Storage::disk('public')->delete(Auth()->user()->image);
        }
        //image
        if ($request->hasFile('savedImage')) {
            $image = $request->file('savedImage')[0];
            $imageName = $image->getClientOriginalName();
            $path = 'avatars/'.$imageName;
            Storage::disk('public')->put($path, file_get_contents($image));

            $user = auth()->user();
            $user->image = $path;
            $user->save();
        }
        //Base64
        else {
            $image = $request->input('savedImage');
            $ext = explode(';base64', $image);
            $ext = explode('/', $ext[0]);
            $ext = $ext[1];

            $image = str_replace('data:image/'.$ext.';base64', '', $image);
            $image = str_replace(' ', '+', $image);

            $imageName = 'avatars/'.str_random(10).'.'.$ext;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $user = auth()->user();
            $user->image = $imageName;
            $user->save();
        }

        return redirect()->back()->with(['message' => 'Profilová fotka se úspěšně nahrála!', 'status' => ToastifyStatus::SUCCESS]);
    }

    public function deleteProfilePicture(Request $request) {
        $user = auth()->user();
        Storage::disk('public')->delete($user->image);
        $user->image = '';
        $user->save();

        return redirect()->back()->with(['message' => 'Profilová fotka byla smazána!', 'status' => ToastifyStatus::SUCCESS]);
    }
}
