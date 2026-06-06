<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\v1\TaskPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        Gate::policy(Task::class, TaskPolicy::class);

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by(Str::lower($request->input('email')) . '|' . $request->ip());
        });
    }
}
