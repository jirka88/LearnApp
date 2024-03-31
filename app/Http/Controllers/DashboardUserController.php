<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Licences;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DashboardUserController extends Controller
{

    /**
     * Vrátí informace o uživateli
     * @return \Inertia\Response
     */
    public function view() {
        $usr =  User::with(['roles', 'accountTypes', 'licences'])->find(auth()->user()->id);
        $roles = [];
        $licences = [];
        $accountTypes = AccountTypes::all();
        if(auth()->user()->role_id == UserRoles::ADMIN) {
           $roles = Roles::all();
           $licences = Licences::all();
        }
        else if(auth()->user()->role_id == UserRoles::OPERATOR) {
            $roles = Roles::whereNot('id', UserRoles::ADMIN)->get();
            $licences = Licences::all();
        }
        else {
            $roles = Roles::find(UserRoles::BASIC_USER)->get();
        }
        return Inertia::render('user/user', compact('usr', 'roles', 'accountTypes', 'licences'));
    }

    /**
     * Aktualizace uživatele
     * @param UpdateRequest $updateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $updateRequest) {
        $typeAccount = $updateRequest->type['id'];
        User::find(auth()->user()->id)->update([
            'firstname' => $updateRequest->firstname,
            'lastname' => $updateRequest->lastname,
            'type_id' => $typeAccount,
        ]);
        return redirect()->back()->with('message', 'Aktualizace úspěšná!');
    }

    /**
     * Resetování hesla
     * @param PasswordResetRequest $passwordResetRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordReset(PasswordResetRequest $passwordResetRequest) {
        if(!Hash::check($passwordResetRequest->oldPassword, auth()->user()->password)) {
            return back()->withErrors(['oldPassword' => 'Staré heslo se liší!']);
        }
        if($passwordResetRequest->oldPassword == $passwordResetRequest->newPassword) {
            return back()->withErrors(['newPasswordSameAsOld' => 'Nové heslo nesmí být stejné jako staré!']);
        }
        User::find(auth()->user()->id)->update([
            'password' => $passwordResetRequest->newPassword,
        ]);
        return redirect()->back()->with(['message' => 'Heslo bylo úspěšně změněno!', 'status' => 'success']);
    }

    /**
     * Nastavení možnosti přijímání sdílení od uživatelů
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeShare(Request $request) {
        $customMessages = [
            'share.required' => 'Je nutné zadat sdílení',
        ];
        $validated = $request->validate([
            'share' => 'required',
        ], $customMessages);
        User::find(auth()->user()->id)->update([
            'canShare' => $validated['share']['id']
        ]);

        return redirect()->back()->with('message', 'Sdílení bylo změněno!');
    }

    /**
     * Redirect k formuláři k nahlášení chyby
     * @return \Inertia\Response
     */
    public function report() {
        return inertia::render('Report');
    }

    /**
     * Získá základní uživatelské statistiky
     * @return \Inertia\Response
     */
    public function getUserStats() {
        $stats = [];
        if(auth()->user()->role_id == 1) {
            $stats = app('App\Http\Controllers\Admin')->getStats();
        }
        return Inertia::render('dashboard', compact('stats'));
    }

    /**
     * Změní profilovou fotku uživatele
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */

    public function changeProfilePicture(Request $request) {
        $customMessages = [
            'savedImage.required' => 'Obrázek není nahrán!',
        ];
        $request->validate([
            'savedImage' => 'required',
        ], $customMessages);

        if(Auth()->user()->image) {
            Storage::disk('public')->delete(Auth()->user()->image);
        }

        //image
        if($request->hasFile('savedImage')) {
            $image = $request->file('savedImage')[0];
            $imageName = $image->getClientOriginalName();
            $path = 'avatars/' . $imageName;
            Storage::disk('public')->put($path, file_get_contents($image));

            $user = User::find(Auth()->user()->id);
            $user->image = $path;
            $user->save();
        }
        //Base64
        else {
            $image = $request->input('savedImage');
            $ext = explode(';base64',$image);
            $ext = explode('/',$ext[0]);
            $ext = $ext[1];

            $image = str_replace('data:image/'.$ext.';base64', '', $image);
            $image = str_replace(' ', '+', $image);

            $imageName = "avatars/" . str_random(10) . '.'.$ext;
            Storage::disk('public')->put($imageName, base64_decode($image));

            $user = User::find(Auth()->user()->id);
            $user->image = $imageName;
            $user->save();
        }
        return redirect()->back();
    }
    public function deleteProfilePicture(Request $request, $user) {
        $user = User::find($user);
        Storage::disk('public')->delete($user->image);
        $user->image = "";
        $user->save();
    }
}
