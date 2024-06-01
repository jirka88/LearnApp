<?php

namespace App\Providers;

use App\Events\ChangedUserSharedSubject;
use App\Events\ChangeUserInformation;
use App\Listeners\forgetUserCache;
use App\Listeners\ForgetUserSharedSubjectCache;
use App\Models\Chapter;
use App\Models\Partition;
use App\Models\User;
use App\Observers\ChapterObserver;
use App\Observers\SubjectObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ChangeUserInformation::class => [
            ForgetUserCache::class
        ],
        ChangedUserSharedSubject::class => [
            ForgetUserSharedSubjectCache::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        Chapter::observe(ChapterObserver::class);
        Partition::observe(SubjectObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents() {
        return false;
    }
}
