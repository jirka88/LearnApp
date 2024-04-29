<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Chapter;
use App\Models\Partition;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\ChapterPolicy;
use App\Policies\PartitionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Partition::class => PartitionPolicy::class,
        User::class => AdminPolicy::class,
        Chapter::class => ChapterPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();
    }
}
