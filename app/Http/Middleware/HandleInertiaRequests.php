<?php

namespace App\Http\Middleware;

use App\Traits\userTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware {
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
    public function version(Request $request): ?string {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array {
        return array_merge(parent::share($request), [
            'flash' => [
                'message' => session('message'),
                'status' => session('status'),
            ],
            'user' => [
                'id' => auth()->user()->id ?? '',
                'firstname' => auth()->user()->firstname ?? '',
                'email' => auth()->user()->email ?? '',
                'role' => auth()->user()->roles ?? '',
                'typeAccount' => auth()->user()->accountTypes->type ?? '',
                'subjects' =>  Cache::rememberForever('subjects', function () {
                    return auth()->user()->patritions ?? '';
                }),
                'licences' => auth()->user()->licences->id ?? '',
                'image' => auth()->user()->image ?? '',
                'sharedSubjects' => $this->getActivedShared(),
            ],
            'permission' => [
                'view' => in_array(auth()->user()?->role_id, [1, 2]),
                'administrator_view' => auth()->user()?->role_id == 1,
                'operator_view' => auth()->user()?->role_id == 2,
            ],
            'settings' => [
                'theme' => $this->getCurrentColor(),
            ],

        ]);
    }
}
