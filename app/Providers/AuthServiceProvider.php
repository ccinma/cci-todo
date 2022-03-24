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

        /**
         * Only allow admins to delete workspaces
         */
        Gate::define('delete-workspace', function (User $user, Workspace $workspace) {
            
            return $workspace->hasMember($user, true);

        });
    }
}
