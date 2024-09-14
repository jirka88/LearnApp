<?php

namespace App\Http\Middleware;

use App\Traits\userTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    use userTrait;

    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => session('message'),
                'status' => session('status'),
            ],
            'user' => [
                'id' => auth()->user()->id ?? '',
                'firstname' => auth()->user()->firstname ?? '',
                'email' => auth()->user()?->id ? Cache::remember('userEmail' . auth()->user()->id, now()->addDays(30), function () {
                    return auth()->user()->email ?? '';
                }) : '',
                'role' =>  auth()->user()?->id ? Cache::remember('userRole' . auth()->user()->id, now()->addDays(30), function () {
                    return auth()->user()->roles ?? '';
                }) : '',
                'typeAccount' => auth()->user()?->id ? Cache::remember('userTypeAccount' . auth()->user()->id, now()->addDays(30), function () {
                    return auth()->user()->accountTypes->type ?? '';
                }) : '',
                'subjects' => auth()->user()?->id ? Cache::remember('subjects' . auth()->user()->id, now()->addDays(30) ,function () {
                    return auth()->user()->patritions ?? '';
                }) : '',
                'licences' => auth()->user()?->id ? Cache::remember('userLicence' . auth()->user()->id,  now()->addDays(30), function () {
                    return auth()->user()->licences->id ?? '';
                }) : '',
                'image' => auth()->user()->image ?? '',
                'sharedSubjects' => $this->getActivedShared(),
                'verified' => auth()->user()?->id ? (auth()->user()->email_verified_at ? true : false) : '',
                'set_language' => session('locale') ?? app()->getLocale(),
            ],
            'permission' => [
                'view' => in_array(auth()->user()?->role_id, [1, 2]),
                'administrator_view' => auth()->user()?->role_id === 1,
                'operator_view' => auth()->user()?->role_id === 2,
                'basic_view' => auth()->user()?->role_id === 4
            ],
            'settings' => [
                'theme' => $this->getCurrentColor(),
                'url' => $request->path(),
            ],

        ]);
    }
}
