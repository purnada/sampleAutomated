<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MunicipalityRequest;
use App\Models\District;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $authUser = $request->user();
        if (! $authUser->can('view municipality')) {
            $notification = Str::toastMsg('You do not have permission to view municipality!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $query = Municipality::with(['district:id,title']);
        $filter = $this->filterQuery($query);
        $municipalities = $filter->orderBy('name')->paginate(50);
        $districts = District::orderBy('title', 'asc')->get();
        $types = Municipality::getTypes();

        return view('admin.pages.municipalities.index', compact('municipalities', 'districts', 'types'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create municipality')) {
            $notification = Str::toastMsg('You do not have permission to create municipality!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $districts = District::orderBy('title', 'asc')->get();
        $types = Municipality::getTypes();

        return view('admin.pages.municipalities.create', compact('districts', 'types'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Municipality\MunicipalityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MunicipalityRequest $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('create municipality')) {
            $notification = Str::toastMsg('You do not have permission to create municipality!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        Municipality::create($request->validated());

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.municipalities.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Municipality $municipality, Request $request)
    {
        $authUser = $request->user();

        if (in_array('view municipality', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view municipality')) {
            return view('admin.pages.municipalities.show', compact('municipality'));
        }

        $notification = Str::toastMsg('You do not have permission to view municipality!', 'warning');

        return redirect()->route('admin.municipalities.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MunicipalityRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipality $municipality, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit municipality')) {
            $notification = Str::toastMsg('You do not have permission to edit municipality!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $districts = District::orderBy('title', 'asc')->get();
        $types = Municipality::getTypes();

        return view('admin.pages.municipalities.edit', compact('municipality', 'districts', 'types'));
    }

    public function update(MunicipalityRequest $request, Municipality $municipality)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit municipality')) {
            $notification = Str::toastMsg('You do not have permission to edit municipality!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $municipality->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.municipalities.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipality $municipality, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit municipality')) {
            $notification = Str::toastMsg('You do not have permission to delete municipality!', 'warning');

            return response($notification, 403);
        }

        $municipality->delete();
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response($notification);

    }

    private function filterQuery($query)
    {
        if (request()->filled('name')) {
            $query->where('name', 'like', '%'.request()->name.'%');
        }
        if (request()->filled('district')) {
            $query->where('district_id', request()->district);
        }
        if (request()->filled('type')) {
            $query->where('municipality_type', request()->type);
        }

        return $query;
    }
}
