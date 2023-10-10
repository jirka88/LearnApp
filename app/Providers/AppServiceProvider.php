<?php

namespace App\Providers;

use App\Models\Partition;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\PartitionPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
