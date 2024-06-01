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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardUserController extends Controller {
    protected $userModel;

    public function __construct(User $user) {
        $this->userModel = $user;
    }

    /**
     * Vrátí informace o uživateli
     *
     * @return \Inertia\Response
     */
    public function view() {
        $usr = auth()->user()->loadMissing(['roles', 'accountTypes', 'licences']);
        $roles = [];
        $licences = [];
        $accountTypes = Cache::rememberForever('accountTypes', function () {
            return AccountTypes::all();
        });
        if (auth()->user()->role_id == UserRoles::ADMIN) {
            $roles = Cache::rememberForever('roles', function () {
                return Roles::all();
            });
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } elseif (auth()->user()->role_id == UserRoles::OPERATOR) {
            $roles = Roles::whereNot('id', UserRoles::ADMIN)->get();
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } else {
            $roles = Roles::find(UserRoles::BASIC_USER)->get();
        }

        return Inertia::render('user/user', compact('usr', 'roles', 'accountTypes', 'licences'));
    }

    /**
     * Aktualizace uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $updateRequest) {
        $typeAccount = $updateRequest->type['id'];
        $this->userModel->getUserById(auth()->user()->id)->update([
            'firstname' => $updateRequest->firstname,
            'lastname' => $updateRequest->lastname,
            'type_id' => $typeAccount,
        ]);
        Cache::forget('userTypeAccount' . auth()->user()->id);

        return redirect()->back()->with(['message' =>  __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Resetování hesla
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordReset(PasswordResetRequest $passwordResetRequest) {
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
        $this->userModel->getUserById(auth()->user()->id)->update([
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
    public function getUserStats(DashboardService $service) {
        $stats = $service->index($this->userModel, auth()->user()->role_id);

        return Inertia::render('dashboard', compact('stats'));
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
