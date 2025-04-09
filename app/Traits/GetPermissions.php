<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

trait GetPermissions
{
    public function getPermissionsViaRole()
    {
        // code...
        $authUser = auth()->user();
        $permissions = Cache::rememberForever('_USER_PERMISSION_', function () use ($authUser) {
            $role = $authUser->roles->first();
            $role = Role::where('id', $role->id)->first();

            return $role->permissions->pluck('name')->toArray();
        });

        return $permissions;
    }
}
