<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create permission', ['only' => ['store', 'create']]);
        $this->middleware('permission:edit permission', ['only' => ['update', 'edit']]);
        $this->middleware('permission:view permission', ['only' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {

        $query = Permission::query();

        $filter = $this->filterQuery($query);

        $permissions = $filter->latest('id')->paginate(50);

        return view('admin.pages.permissions.index', compact('permissions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $new = Permission::getPermissions();
        $already = Permission::pluck('name')->toArray();
        $merged = array_merge($new, $already);
        $permissions = array_diff($new, $already);
        if (count($permissions) < 1) {
            $notification = Str::toastMsg('New Permission is not found', 'warning');

            return redirect()->route('admin.permissions.index')->with($notification);
        }

        return view('admin.pages.permissions.create', compact('permissions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request): RedirectResponse
    {
        if (! is_array($request->name)) {
            $notification = Str::toastMsg(config('custom.msg.create'), 'success');

            return redirect()->route('admin.permissions.index')->with($notification);
        }
        foreach ($request->name as $name) {
            Permission::create(['name' => $name, 'guard_name' => 'web']);
        }

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.permissions.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission): View
    {
        return view('admin.pages.permissions.show', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission, Request $request): View
    {
        $permissions = Permission::getPermissions();

        return view('admin.pages.permissions.edit', compact('permission', 'permissions'));

    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.permissions.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('delete permission')) {
            $notification = Str::toastMsg('You do not have permission to delete permission!', 'warning');

            return response($notification, 403);
        }
        $permission->delete();
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response($notification);

    }

    private function filterQuery($query)
    {
        if (request()->filled('title')) {
            $query->where('title', 'like', '%'.request()->title.'%');
        }

        return $query;
    }
}
