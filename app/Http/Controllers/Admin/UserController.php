<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Sector;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserShift;
use App\Traits\GetPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use GetPermissions;

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
    public function index(Request $request): View|RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('view user')) {
            $notification = Str::toastMsg('You do not have permission to view user!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $param = $request->fullUrlWithQuery(collect($request->query)->toArray());

        $query = User::with('roles');
        $filter = $this->filterQuery($query);
        $users = $filter->latest('id')->paginate(50)->setPath($param);
        $roles = Role::pluck('name');

        // return $users;
        return view('admin.pages.users.index', compact('users', 'roles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create user')) {
            $notification = Str::toastMsg('You do not have permission to create user!', 'warning');

            return redirect()->route('admin.users.index')->with($notification);
        }
        $permissions = Permission::pluck('name');
        $roles = Role::pluck('name');
        $sectors = Sector::pluck('title');
        $weekends = User::getWeekends();

        return view('admin.pages.users.create', compact('permissions', 'roles', 'sectors', 'weekends'));

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request): RedirectResponse
    {
        // return $request->all();
        $authUser = $request->user();
        if (! $authUser->can('create user')) {
            $notification = Str::toastMsg('You do not have permission to create user!', 'warning');

            return redirect()->route('admin.users.index')->with($notification);
        }
        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
        ]);
        if (! $user) {
            $notification = Str::toastMsg('Something went wrong, please contact developer!', 'error');

            return redirect()->route('admin.users.index')->with($notification);
        }
        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')
                ->usingName($user->name)->toMediaCollection('user_image');
        }
        $user->syncPermissions($request->permission);
        $user->syncRoles($request->role);
        UserDetail::create([
            'user_id' => $user->id,
            'sector' => $request->sectors,
            'job' => $request->job,
            'weekend' => $request->weekends,
            'contact_number' => $request->contact_number,
            'max_booking' => $request->max_booking ?? 0,
        ]);
        if ($request->shift) {
            foreach ($request->shift as $key => $shift) {
                UserShift::create([
                    'user_id' => $user->id,
                    'start_time' => $shift['start_time'],
                    'end_time' => $shift['end_time'],
                ]);
            }
        }
        Cache::forget('_USER_PERMISSION_');
        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.users.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): View
    {
        return view('admin.pages.users.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Request $request): View|RedirectResponse
    {
        $user->load(['detail', 'shifts']);
        $authUser = $request->user();
        if (! $authUser->can('edit user')) {
            $notification = Str::toastMsg('You do not have permission to edit user!', 'warning');

            return redirect()->route('admin.users.index')->with($notification);
        }
        $permissions = Permission::pluck('name');
        $roles = Role::pluck('name');
        $user_permissions = $user->permissions->pluck('name')->toArray();
        $user_role = $user->roles->first();
        $user_roles = null;
        if ($user_role) {
            $user_roles = $user_role->name;
        }
        $sectors = Sector::pluck('title');
        $weekends = User::getWeekends();

        return view('admin.pages.users.edit', compact('user', 'roles', 'permissions', 'user_roles', 'user_permissions', 'sectors', 'weekends'));

    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('edit user')) {
            $notification = Str::toastMsg('You do not have permission to edit user!', 'warning');

            return redirect()->route('admin.users.index')->with($notification);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->password) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request['password']),
            ];
        }
        $user->update($data);

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')
                ->usingName($user->name)->toMediaCollection('user_image');
        }
        $user->syncPermissions($request->permission);
        $user->syncRoles($request->role);

        UserDetail::updateOrCreate(['user_id' => $user->id], [

            'sector' => $request->sectors,
            'job' => $request->job,
            'contact_number' => $request->contact_number,
            'weekend' => $request->weekends,
            'max_booking' => $request->max_booking ?? 0,
        ]);
        UserShift::where('user_id', $user->id)->delete();
        if ($request->shift) {
            foreach ($request->shift as $key => $shift) {
                UserShift::create([
                    'user_id' => $user->id,
                    'start_time' => $shift['start_time'],
                    'end_time' => $shift['end_time'],
                ]);
            }
        }
        Cache::forget('_USER_PERMISSION_');
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.users.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('delete user')) {
            $notification = Str::toastMsg('You do not have permission to delete user!', 'warning');

            return response($notification, 403);
        }
        $user->delete();
        Cache::forget('_USER_PERMISSION_');
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response($notification);

    }

    private function filterQuery($query)
    {
        if (request()->filled('name')) {
            $query->where('name', 'like', '%'.request()->name.'%');
        }

        if (request()->filled('role')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', request()->role);

                return $q;
            });
        }

        return $query;
    }
}
