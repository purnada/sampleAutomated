<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Traits\GetPermissions;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use GetPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('view role')) {
            $notification = Str::toastMsg('You do not have permission to create role!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $query = Role::query();

        $filter = $this->filterQuery($query);

        $roles = $filter->latest('id')->paginate(15);

        return view('admin.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create role')) {
            $notification = Str::toastMsg('You do not have permission to create role!', 'warning');

            return redirect()->route('admin.roles.index')->with($notification);
        }
        $permissions = Permission::pluck('name');

        return view('admin.pages.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create role')) {
            $notification = Str::toastMsg('You do not have permission to create role!', 'warning');

            return redirect()->route('admin.roles.index')->with($notification);
        }
        Role::create($request->validated() + ['guard_name' => 'web']);

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.roles.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role): View
    {
        return view('admin.pages.roles.show', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('edit role')) {
            $notification = Str::toastMsg('You do not have permission to edit role!', 'warning');

            return redirect()->route('admin.roles.index')->with($notification);
        }
        $permissions = Permission::pluck('name');
        $role_permissions = $role->permissions->pluck('name')->toArray();

        // return $role_permissions;
        return view('admin.pages.roles.edit', compact('role', 'permissions', 'role_permissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit role')) {
            $notification = Str::toastMsg('You do not have permission to edit role!', 'warning');

            return redirect()->route('admin.roles.index')->with($notification);
        }
        $name = $request->name;
        if ($role->name != 'Super Admin') {
            $name = $role->name;
        }

        $role->update(['name' => $name]);
        $role->syncPermissions($request->permission);
        Cache::forget('_USER_PERMISSION_');
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.roles.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, Request $request): JsonResponse|RedirectResponse|Responsable
    {
        if ($role->name == 'Super Admin') {
            $notification = Str::toastMsg('You do not have permission to delete role!', 'warning');

            return response()->json(['message' => $notification], 403);
        }
        $authUser = $request->user();
        if (! $authUser->can('delete role')) {
            $notification = Str::toastMsg('You do not have permission to edit role!', 'warning');

            return redirect()->route('admin.roles.index')->with($notification);
        }

        $role->delete();
        Cache::forget('_USER_PERMISSION_');
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response()->json(['message' => $notification]);

    }

    private function filterQuery($query)
    {
        if (request()->filled('title')) {
            $query->where('title', 'like', '%'.request()->title.'%');
        }

        return $query;
    }
}
