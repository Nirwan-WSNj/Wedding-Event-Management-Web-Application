<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Booking;
use App\Policies\BookingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role-based access
        Gate::define('manage-bookings', function ($user) {
            return $user->isAdmin() || $user->isManager();
        });

        Gate::define('approve-bookings', function ($user) {
            return $user->isAdmin() || $user->isManager();
        });

        Gate::define('view-all-bookings', function ($user) {
            return $user->isAdmin() || $user->isManager();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}
