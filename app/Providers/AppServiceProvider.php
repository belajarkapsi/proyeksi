<?php

namespace App\Providers;

use App\Models\Kamar;
use App\Models\Service;
use App\Models\User;
use App\Policies\KamarPolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Gate::policy(Kamar::class, KamarPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
    }
}
