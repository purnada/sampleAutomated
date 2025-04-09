<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Language;
use App\Models\Setting;
use App\Models\SettingDetail;
use App\Traits\GetPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
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
        if (! $authUser->can('view setting')) {
            $notification = Str::toastMsg('You do not have permission to view setting!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $protocols = Setting::getProtocols();
        $setting = Setting::with(['settingEmail'])->first();
        if ($setting) {
            $languages = Language::with(['settingDetail' => function ($q) use ($setting) {
                $q->where('setting_id', $setting->id);
            }])->orderBy('default', 'desc')->where('status', '1')->get();

            return view('admin.pages.settings.edit', compact('setting', 'languages', 'protocols'));
        }
        $languages = Language::orderBy('default', 'desc')->where('status', '1')->get();

        // return $languages;
        return view('admin.pages.settings.create', compact('protocols', 'languages'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $authUser = $request->user();

        if (in_array('view setting', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view setting')) {
            return view('admin.pages.settings.create');
        }
        $notification = Str::toastMsg('You do not have permission to view setting!', 'warning');

        return redirect()->route('admin.settings.index')->with($notification);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request): RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create setting')) {
            $notification = Str::toastMsg('You do not have permission to create setting!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $setting = Setting::create($request->validated());
        $setting->settingEmail()->create($request->validated());
        foreach ($request->detail as $language_id => $detail) {
            SettingDetail::create([
                'setting_id' => $setting->id,
                'language_id' => $language_id,
                'name' => $detail['name'],
                'telephone' => $detail['telephone'],
                'address' => $detail['address'],
                'meta_title' => $detail['meta_title'],
                'meta_keyword' => $detail['meta_keyword'],
                'meta_description' => $detail['meta_description'],
            ]);
        }
        if ($request->hasFile('logo')) {
            $setting->addMediaFromRequest('logo')->toMediaCollection('setting_logo');
        }

        if ($request->hasFile('icon')) {
            $setting->addMediaFromRequest('icon')->toMediaCollection('setting_icon');
        }

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.settings.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();

        if (in_array('view setting', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view setting')) {
            return view('admin.pages.settings.show', compact('setting'));
        }

        $notification = Str::toastMsg('You do not have permission to view setting!', 'warning');

        return redirect()->route('admin.settings.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SettingRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();

        if (in_array('edit setting', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('edit setting')) {
            return view('admin.pages.settings.edit', compact('setting'));
        }
        $notification = Str::toastMsg('You do not have permission to edit setting!', 'warning');

        return redirect()->route('admin.settings.index')->with($notification);
    }

    public function update(SettingRequest $request, Setting $setting): RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('edit setting')) {
            $notification = Str::toastMsg('You do not have permission to edit setting!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $setting->update($request->validated());

        $setting->settingEmail->update($request->validated());
        foreach ($request->detail as $language_id => $detail) {
            SettingDetail::updateOrCreate([
                'setting_id' => $setting->id,
                'language_id' => $language_id,
            ], [
                'name' => $detail['name'],
                'telephone' => $detail['telephone'],
                'address' => $detail['address'],
                'meta_title' => $detail['meta_title'],
                'meta_keyword' => $detail['meta_keyword'],
                'meta_description' => $detail['meta_description'],
            ]);
        }
        if ($request->hasFile('logo')) {
            $setting->addMediaFromRequest('logo')->toMediaCollection('setting_logo');
        }

        if ($request->hasFile('icon')) {
            $setting->addMediaFromRequest('icon')->toMediaCollection('setting_icon');
        }
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.settings.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting, Request $request)
    {
        $authUser = $request->user();

        if (in_array('delete setting', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('delete setting')) {
            $setting->delete();
            $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

            return response($notification);
        }
        $notification = Str::toastMsg('You do not have permission to delete setting!', 'warning');

        return response($notification, 403);
    }

    private function filterQuery($query)
    {
        if (request()->filled('title')) {
            $query->where('title', 'like', '%'.request()->title.'%');
        }

        return $query;
    }
}
