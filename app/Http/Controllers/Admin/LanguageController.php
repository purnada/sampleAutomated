<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create language', ['only' => ['store', 'create']]);
        $this->middleware('permission:edit language', ['only' => ['update', 'edit']]);
        $this->middleware('permission:view language', ['only' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {

        $query = Language::query();
        $filter = $this->filterQuery($query);
        $languages = $filter->latest('id')->paginate(15);

        return view('admin.pages.languages.index', compact('languages'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View
    {

        return view('admin.pages.languages.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request): RedirectResponse
    {

        $default = $request->default_value ? '1' : '0';

        $status = $request->status_value ? '1' : '0';
        if ($request->default_value) {
            Language::where('default', '1')->update(['default' => '0']);
        }

        Language::create($request->validated() + ['default' => $default, 'status' => $status]);

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');

        return redirect()->route('admin.languages.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language, Request $request): RedirectResponse
    {

        $notification = Str::toastMsg('You do not have permission to view language!', 'warning');

        return redirect()->route('admin.languages.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LanguageRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language, Request $request): View
    {

        return view('admin.pages.languages.edit', compact('language'));
    }

    public function update(LanguageRequest $request, Language $language): RedirectResponse
    {

        if ($language->default == '1' && ! $request->default_value) {
            $notification = Str::toastMsg('You can not change the default value of this language, first of all make another language default!', 'warning');

            return redirect()->route('admin.languages.index')->with($notification);
        }

        $default = $request->default_value ? '1' : '0';

        $status = $request->status_value ? '1' : '0';
        if ($request->default_value && $language->default != '1') {
            Language::where('default', '1')->update(['default' => '0']);
        }

        $language->update($request->validated() + ['default' => $default, 'status' => $status]);
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.languages.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language, Request $request)
    {
        $authUser = $request->user();
        if ($language->default == '1') {
            $notification = Str::toastMsg('You can not delete default language!', 'warning');

            return response($notification, 403);
        }
        if (! $authUser->can('delete language')) {
            $notification = Str::toastMsg('You do not have permission to delete language!', 'warning');

            return response($notification, 403);
        }
        $language->delete();
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
