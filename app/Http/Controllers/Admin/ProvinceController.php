<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProvinceRequest;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('view province')) {
            $notification = Str::toastMsg('You do not have permission to view province!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $query = Province::query();
        $filter = $this->filterQuery($query);
        $provinces = $filter->latest('id')->paginate(15);

        return view('admin.pages.provinces.index', compact('provinces'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create province')) {
            $notification = Str::toastMsg('You do not have permission to create province!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        return view('admin.pages.provinces.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Province\ProvinceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvinceRequest $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create province')) {
            $notification = Str::toastMsg('You do not have permission to create province!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        Province::create($request->validated());

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.provinces.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Province $province, Request $request)
    {
        $authUser = $request->user();

        if (in_array('view province', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view province')) {
            return view('admin.pages.provinces.show', compact('province'));
        }

        $notification = Str::toastMsg('You do not have permission to view province!', 'warning');

        return redirect()->route('admin.provinces.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProvinceRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit province')) {
            $notification = Str::toastMsg('You do not have permission to edit province!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        return view('admin.pages.provinces.edit', compact('province'));
    }

    public function update(ProvinceRequest $request, Province $province)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit province')) {
            $notification = Str::toastMsg('You do not have permission to edit province!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $province->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.provinces.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit province')) {
            $notification = Str::toastMsg('You do not have permission to delete province!', 'warning');

            return response($notification, 403);
        }

        $province->delete();
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
