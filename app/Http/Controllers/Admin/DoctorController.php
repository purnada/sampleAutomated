<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GetPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    use GetPermissions;

    public function __construct()
    {

        $this->middleware('permission:view doctor', ['only' => ['show', 'index']]);
    }

    public function index(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('view doctor')) {
            $notification = Str::toastMsg('You do not have permission to view doctor!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $param = $request->fullUrlWithQuery(collect($request->query)->toArray());

        $query = User::with(['detail'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'doctor');

                return $q;
            });
        $filter = $this->filterQuery($query);
        $users = $filter->latest('id')->paginate(50)->setPath($param);

        return view('admin.pages.doctors.index', compact('users'));
    }

    private function filterQuery($query)
    {
        if (request()->filled('name')) {
            $query->where('name', 'like', '%'.request()->name.'%');
        }

        return $query;
    }
}
