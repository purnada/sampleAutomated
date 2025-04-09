<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DistrictRequest;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('view district')) {
            $notification = Str::toastMsg('You do not have permission to view district!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $query = District::query();
        $filter = $this->filterQuery($query);
        $districts = $filter->latest('id')->paginate(100);

        return view('admin.pages.districts.index', compact('districts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create district')) {
            $notification = Str::toastMsg('You do not have permission to create district!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $provinces = Province::pluck('id', 'title');

        return view('admin.pages.districts.create', compact('provinces'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\District\DistrictRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create district')) {
            $notification = Str::toastMsg('You do not have permission to create district!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        District::create($request->validated());

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.districts.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(District $district, Request $request)
    {
        $authUser = $request->user();

        if (in_array('view district', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view district')) {
            return view('admin.pages.districts.show', compact('district'));
        }

        $notification = Str::toastMsg('You do not have permission to view district!', 'warning');

        return redirect()->route('admin.districts.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DistrictRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit district')) {
            $notification = Str::toastMsg('You do not have permission to edit district!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $provinces = Province::pluck('id', 'title');

        return view('admin.pages.districts.edit', compact('district', 'provinces'));
    }

    public function update(DistrictRequest $request, District $district)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit district')) {
            $notification = Str::toastMsg('You do not have permission to edit district!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $district->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.districts.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit district')) {
            $notification = Str::toastMsg('You do not have permission to delete district!', 'warning');

            return response($notification, 403);
        }

        $district->delete();
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
