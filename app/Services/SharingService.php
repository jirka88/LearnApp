<?php

namespace App\Services;

use App\Http\Resources\UserSelectResource;
use App\Models\Partition;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SharingService {
    /**
     * Vrátí uživatelovi sdílené předměty a oprávnění
     *
     * @return array
     */
    public function index($user) {
        $partitions = $user->patritions()->where('permission_id', null)
            ->with(['users' => function ($query) {
                UserSelectResource::make($query->whereNot('user_id', auth()->user()->id)->get());
            }])->get();
        $partitions->each(function ($subject) {
            $subject->users->each(function ($user) {
                $user->permission['name'] = Permission::where('id', $user->permission->permission_id)->pluck('permission')->first();
            });
        });
        $subjects = $partitions->filter(function ($patrition) {
            return $patrition->users->isNotEmpty();
        });
        $permission = Cache::rememberForever('permission', function () {
            return Permission::all();
        });

        return ['subjects' => $subjects, 'permissions' => $permission];
    }

    /**
     * Vrátí nabídky ke sdílení předmětů
     *
     * @return mixed
     */
    public function showOfferShare($user): Collection {
        $subjects = $user
            ->patritions()
            ->where('accepted', false)
            ->with(['Users' => function ($query2) {
                UserSelectResource::make($query2->where('permission_id', null))->first();
            }])->get();

        return $subjects;
    }

    /**
     * Odmítnutí sdílení předmětu
     *
     * @return mixed
     */
    public function refuseShare($user, $request_slug): Partition {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request_slug);
        $user->patritions()->detach($subject->id);

        return $subject;
    }
}
