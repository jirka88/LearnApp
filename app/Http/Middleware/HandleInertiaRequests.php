<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Traits\userTrait;

class HandleInertiaRequests extends Middleware
{
    use userTrait;
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => session('message'),
            ],
            'user' => [
                'id' => auth()->user()->id ?? '',
                'firstname' => auth()->user()->firstname ?? '',
                'email' => auth()->user()->email ?? '',
                'role' => auth()->user()->roles ?? '',
                'typeAccount' => auth()->user()->accountTypes->type ?? '',
                'subjects' => auth()->user()->patritions ?? '',
                'licences' => auth()->user()->licences->id ?? '',
                'image' => auth()->user()->image ?? '',
                'sharedSubjects' => $this->getActivedShared()
            ],
            'permission' => [
                'view' => in_array(auth()->user()?->role_id, [1, 2]),
                'administrator_view' => auth()->user()?->role_id == 1,
                'operator_view' => auth()->user()?->role_id == 2,
            ],
            'settings' => [
                'theme' => Settings::get('color')->first(),
            ]

        ]);
    }
}
