<?php

namespace App\Providers;

use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        // Gates: named yes/no rules based on the user's role

        // Only Officers can manage the SSG (events, fines, and so on)
        Gate::define('manage-ssg', fn (User $user) => $user->hasRole('Officer'));

        // Officers and Advisers can view reports
        Gate::define('view-reports', fn (User $user) => $user->hasRole('Officer', 'Adviser'));

        // Only Students use the attendance (QR) feature
        Gate::define('use-attendance', fn (User $user) => $user->hasRole('Student'));

        // Weather: give the weather box its data every time it is shown
        View::composer('partials.weather', function ($view) {
            $view->with('weather', app(WeatherService::class)->current());
        });
    }
}