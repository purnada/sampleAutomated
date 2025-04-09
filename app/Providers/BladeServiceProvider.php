<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['admin.includes.sidebar'], function ($view) {
            $authUser = auth()->user();
            $permissions = Cache::rememberForever('_USER_PERMISSION_', function () use ($authUser) {
                $role = $authUser->roles->first();
                $role = Role::where('id', $role->id)->first();

                return $role->permissions->pluck('name')->toArray();
            });

            $view->with('permissions', $permissions);
        });
    }
}
