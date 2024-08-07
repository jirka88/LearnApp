<?php

namespace App\Services;

use App\Enums\ToastifyStatus;
use App\Events\ChangedUserSharedSubject;
use App\Http\Resources\UserSelectResource;
use App\Models\Partition;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\Type\VoidType;

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
     * Vytvoření sdílení
     * @param $validated
     * @param $auth_user
     * @return Collection
     */
    public function store($validated, $auth_user): Array {

        $sendMessage = __('share.warning.send');
        $status = ToastifyStatus::SUCCESS;
        foreach ($validated['users'] as $email) {
            $user = User::where('email', $email)->firstOrFail();
            if ($user->patritions()->where('partition_id', $validated['subject'])->first() == null) {
                $user->patritions()->attach($validated['subject'], ['permission_id' => (int) $validated['permission'], 'accepted' => 0]);
            } else {
                $sendMessage = __('share.warning.again_send');
                $status = ToastifyStatus::INFO;
            }
        }
        event(new ChangedUserSharedSubject($auth_user, $user));

        return ['message' => $sendMessage, 'status' => $status];
    }

    /**
     * Aktualizace sdílení
     * @param $request_email
     * @param $request_subject
     * @param $request_permission
     * @return Void
     */
    public function update($request_email, $request_subject, $request_permission): Void {
        $userModel = app('\App\Models\User');
        $user = $userModel->getUserByEmail($request_email);
        $permission = $request_permission;
        $subject = Partition::findOrFail($request_subject);
        $user->patritions()->updateExistingPivot($subject->id, ['permission_id' => $permission['id']]);
    }

    /**
     * Vymazání sdílení
     * @param $slug
     * @param $user
     * @param $auth_user
     * @return Void
     */
    public function delete($slug, $user, $auth_user) :Void {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($slug);
        $user->patritions()->detach($subject->id);
        event(new ChangedUserSharedSubject($auth_user, $user));
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
            ->with(['Users' => function ($query) {
                UserSelectResource::make($query->where('permission_id', null))->first();
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

        $userModel = app('\App\Models\User');
        $userOwner = $userModel->getUserById($subject->created_by);

        event(new ChangedUserSharedSubject($user, $userOwner));
        return $subject;
    }

    /**
     * @param $request_slug
     * @return void
     *
     * Přijmutí sdílení předmětu
     */
    public function acceptShare($request_slug): void {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request_slug);
        auth()->user()->patritions()->updateExistingPivot($subject->id, ['accepted' => 1]);
        event(new ChangedUserSharedSubject(auth()->user()));
    }
}
