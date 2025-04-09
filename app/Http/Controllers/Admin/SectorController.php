<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectorRequest;
use App\Models\Sector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('view sector')) {
            $notification = Str::toastMsg('You do not have permission to view sector!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $query = Sector::query();
        $filter = $this->filterQuery($query);
        $sectors = $filter->latest('id')->paginate(15);

        return view('admin.pages.sectors.index', compact('sectors'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create sector')) {
            $notification = Str::toastMsg('You do not have permission to create sector!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        return view('admin.pages.sectors.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SectorRequest $request): RedirectResponse|View
    {
        $authUser = $request->user();
        if (! $authUser->can('create sector')) {
            $notification = Str::toastMsg('You do not have permission to create sector!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        Sector::create($request->validated());

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.sectors.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $sector, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();

        if (in_array('view sector', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view sector')) {
            return view('admin.pages.sectors.show', compact('sector'));
        }

        $notification = Str::toastMsg('You do not have permission to view sector!', 'warning');

        return redirect()->route('admin.sectors.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SectorRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Sector $sector, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('edit sector')) {
            $notification = Str::toastMsg('You do not have permission to edit sector!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        return view('admin.pages.sectors.edit', compact('sector'));
    }

    public function update(SectorRequest $request, Sector $sector)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit sector')) {
            $notification = Str::toastMsg('You do not have permission to edit sector!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $sector->update($request->validated());
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.sectors.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sector $sector, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit sector')) {
            $notification = Str::toastMsg('You do not have permission to delete sector!', 'warning');

            return response($notification, 403);
        }

        $sector->delete();
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
