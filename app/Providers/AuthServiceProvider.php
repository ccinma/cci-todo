<?php

namespace App\Providers;

use App\User;
use App\Workspace;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('collaborate', function (User $user, Workspace $workspace) {

            return !! $workspace->members()->where('id', $user->id)->first();
            
        });

        /**
         * Only allow admins to delete workspaces
         */
        Gate::define('manage-workspace', function (User $user, Workspace $workspace) {
            
            return !! $workspace->members()->where([['user_id', $user->id], ['isAdmin', true]])->first();

        });
    }
}
