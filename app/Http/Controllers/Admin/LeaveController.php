<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeaveRequest;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('view leave')) {
            $notification = Str::toastMsg('You do not have permission to view leave!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $query = Leave::with('user:id,name');
        $filter = $this->filterQuery($query);
        $leaves = $filter->latest('id')->paginate(15);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');

            return $q;
        })->orderBy('name', 'asc')->get();

        return view('admin.pages.leaves.index', compact('leaves', 'users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create leave')) {
            $notification = Str::toastMsg('You do not have permission to create leave!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');

            return $q;
        })->orderBy('name', 'asc')->get();

        return view('admin.pages.leaves.create', compact('users'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Leave\LeaveRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveRequest $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create leave')) {
            $notification = Str::toastMsg('You do not have permission to create leave!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        Leave::create($request->validated());

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.leaves.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leaf, Request $request)
    {

        $authUser = $request->user();

        if (in_array('view leave', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view leave')) {
            return view('admin.pages.leaves.show', compact('leave'));
        }

        $notification = Str::toastMsg('You do not have permission to view leave!', 'warning');

        return redirect()->route('admin.leaves.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LeaveRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leaf, Request $request)
    {
        $leave = $leaf;
        $authUser = $request->user();
        if (! $authUser->can('edit leave')) {
            $notification = Str::toastMsg('You do not have permission to edit leave!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');

            return $q;
        })->orderBy('name', 'asc')->get();

        return view('admin.pages.leaves.edit', compact('leave', 'users'));
    }

    public function update(LeaveRequest $request, Leave $leaf)
    {
        $leave = $leaf;
        $authUser = $request->user();
        if (! $authUser->can('edit leave')) {
            $notification = Str::toastMsg('You do not have permission to edit leave!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $leave->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.leaves.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leaf, Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('edit leave')) {
            $notification = Str::toastMsg('You do not have permission to delete leave!', 'warning');

            return response($notification, 403);
        }

        $leaf->delete();
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response($notification);

    }

    private function filterQuery($query)
    {
        if (request()->filled('user')) {
            $query->where('user_id', request()->user);
        }
        if (request()->filled('start_date')) {
            $query->where('start_date', '>=', request()->start_date);
        }
        if (request()->filled('end_date')) {
            $query->where('end_date', '<=', request()->end_date);
        }

        return $query;
    }
}
