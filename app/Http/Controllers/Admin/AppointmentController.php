<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentRequest;
use App\Library\UserStaticData;
use App\Models\Appointment;
use App\Models\Disease;
use App\Models\Leave;
use App\Models\Province;
use App\Models\User;
use App\Models\UserDetail;
use App\Traits\NepaliDate;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AppointmentController extends Controller
{
    use NepaliDate;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('view appointment')) {
            $notification = Str::toastMsg('You do not have permission to view appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $role = $authUser->roles->first();
        $user_role = null;
        if ($role) {
            $user_role = $role->name;
        }
        $param = $request->fullUrlWithQuery(collect($request->query)->toArray());

        $query = Appointment::with(['user:id,name', 'doctor:id,name', 'province:id,title', 'district', 'municipality', 'diseases', 'child']);
        $filter = $this->filterQuery($query, $user_role);
        $appointments = $filter->latest('id')->paginate(5)->setPath($param);
        $doctors = [];
        if ($user_role != 'Doctor') {
            $doctors = User::whereHas('roles', function ($q) {
                $q->where('name', 'Doctor');

                return $q;
            })->pluck('name', 'id');

        }
        $status = Appointment::getStatus();

        return view('admin.pages.appointments.index', compact('appointments', 'doctors', 'user_role', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('create appointment')) {
            $notification = Str::toastMsg('You do not have permission to create appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }

        $doctors = User::with('detail')->whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');

            return $q;
        })->get();
        $provinces = Province::pluck('id', 'title');

        return view('admin.pages.appointments.create', compact('doctors', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request): RedirectResponse
    {

        $authUser = $request->user();
        if (! $authUser->can('create appointment')) {
            $notification = Str::toastMsg('You do not have permission to create appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $doctor = UserDetail::where('user_id', $request->doctor_id)->first();
        if (! $doctor) {
            $notification = Str::toastMsg('Doctor not found!', 'warning');

            return redirect()->back()->with($notification);
        }
        if ($doctor->max_booking > 0) {
            $total_booking = Appointment::where('doctor_id', $request->doctor_id)
                ->where('nepali_date', $request->nepali_date)->count();
            if ($total_booking >= $doctor->max_booking) {
                $notification = Str::toastMsg('Doctor quota is full! Allow quota is '.$doctor->max_booking, 'warning');

                return redirect()->back()->with($notification);
            }
        }

        $appointment_id = null;
        $check = $this->checkAppointment($request->appointment_date, $appointment_id, $request->doctor_id);
        if ($check > 0) {
            return redirect()->back()->withInput()->withErrors(['doctor_id' => 'Doctor is not available for this time']);
        }

        $appointment = Appointment::create($request->validated() + ['booked_by' => $authUser->id]);
        if ($appointment) {
            if ($request->disease) {
                $diseases = explode(',', $request->disease);
                if (is_array($diseases)) {
                    foreach ($diseases as $dis) {
                        Disease::create([
                            'appointment_id' => $appointment->id,
                            'name' => $dis,
                        ]);
                    }
                }
            }

        }

        $notification = Str::toastMsg(config('custom.msg.create'), 'success');
        if ($request->page && $request->page == 'calendar') {
            return redirect()->back()->with($notification);
        }

        return redirect()->route('admin.appointments.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();

        if (in_array('view appointment', $this->getPermissionsViaRole()) || $authUser->hasPermissionTo('view appointment')) {
            return view('admin.pages.appointments.show', compact('appointment'));
        }

        $notification = Str::toastMsg('You do not have permission to view appointment!', 'warning');

        return redirect()->route('admin.appointments.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('edit appointment')) {
            $notification = Str::toastMsg('You do not have permission to edit appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $doctors = User::with('detail')->whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');

            return $q;
        })->get();
        $provinces = Province::pluck('id', 'title');

        return view('admin.pages.appointments.edit', compact('appointment', 'doctors', 'provinces'));
    }

    public function update(AppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('edit appointment')) {
            $notification = Str::toastMsg('You do not have permission to edit appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $doctor = UserDetail::where('user_id', $request->doctor_id)->first();
        if (! $doctor) {
            $notification = Str::toastMsg('Doctor not found!', 'warning');

            return redirect()->back()->with($notification);
        }
        if ($doctor->max_booking > 0) {
            $total_booking = Appointment::where('doctor_id', $request->doctor_id)
                ->where('nepali_date', $request->nepali_date)->where('id', '!=', $appointment->id)->count();
            if ($total_booking >= $doctor->max_booking) {
                $notification = Str::toastMsg('Doctor quota is full! Allow quota is '.$doctor->max_booking, 'warning');

                return redirect()->back()->with($notification);
            }
        }
        $check = $this->checkAppointment($request->appointment_date, $appointment->id, $request->doctor_id);
        if ($check > 0) {
            return redirect()->back()->withInput()->withErrors(['doctor_id' => 'Doctor is not available for this time']);
        }

        $appointment->update($request->validated());
        Disease::where('appointment_id', $appointment->id)->delete();
        if ($request->disease) {
            $diseases = explode(',', $request->disease);
            if (is_array($diseases)) {
                foreach ($diseases as $dis) {
                    Disease::create([
                        'appointment_id' => $appointment->id,
                        'name' => $dis,
                    ]);
                }
            }
        }
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.appointments.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment, Request $request)
    {
        $authUser = $request->user();
        if (! $authUser->can('edit appointment')) {
            $notification = Str::toastMsg('You do not have permission to delete appointment!', 'warning');

            return response($notification, 403);
        }

        $appointment->delete();
        $notification = Str::toastMsg(config('custom.msg.delete'), 'success');

        return response($notification);
    }

    private function filterQuery($query, $role)
    {
        if (request()->filled('name')) {
            $query->where('name', 'like', '%'.request()->name.'%');
        }
        if (request()->filled('contact_number')) {
            $query->where('contact_number', 'like', '%'.request()->contact_number.'%');
        }
        if ($role == 'Doctor') {
            $query->where('doctor_id', auth()->user()->id);
        } else {
            if (request()->filled('doctor')) {
                $query->where('doctor_id', request()->doctor);
            }
        }

        if (request()->filled('appointment_date')) {
            $query->whereDate('appointment_date', request()->appointment_date);
        }
        if (request()->filled('status')) {
            $query->where('status', request()->status);
        }

        return $query;
    }

    public function getDates(Request $request)
    {
        $this->validate($request, [
            'doctor' => ['required', 'integer'],
            'date_time' => ['nullable', 'date', 'date_format:Y-m-d'],
            'appointment_id' => ['nullable', 'integer'],
        ]);

        $doctor = User::with(['detail'])->where('id', $request->doctor)->first();

        if ($doctor) {
            if (! $request->date_time) {
                $notification = [
                    'message' => null,
                    'data' => null,
                    'sectors' => UserStaticData::getDoctorSectors($doctor->id, ''),
                    'booking_status' => null,
                ];

                return response($notification);
            }

            $filter_date = $this->convertNepaliDateToPlainEnglishDate($request->date_time);
            $today = Carbon::parse($filter_date)->format('l');
            if (in_array(strtoupper($today), $doctor->detail->weekend)) {

                $notification = [
                    'message' => '<div class="bg-white dark:bg-bgdark border border-danger alert text-danger" role="alert"> <span class="font-bold">'.$doctor->name.' is on weekend in '.$filter_date.'</span></div>',
                    'data' => null,
                    'sectors' => UserStaticData::getDoctorSectors($doctor->id, ''),
                    'booking_status' => null,
                ];

                return response($notification);

            }

            $check_leave = Leave::where('user_id', $doctor->id)->where('start_date', '<=', $filter_date)->where('end_date', '>=', $filter_date)->first();
            if ($check_leave) {

                $notification = [
                    'message' => '<div class="bg-white dark:bg-bgdark border border-danger alert text-danger" role="alert"> <span class="font-bold">'.$doctor->name.' is on Leave in '.$filter_date.'</span></div>',
                    'data' => null,
                    'sectors' => UserStaticData::getDoctorSectors($doctor->id, ''),
                    'booking_status' => null,
                ];

                return response($notification);
            }
            $booking_status = null;
            if ($doctor->detail->max_booking > 0) {
                $booking = Appointment::where('doctor_id', $doctor->id)
                    ->where('nepali_date', $request->date_time);

                $total_booking = $booking->count();

                $booking_status = $total_booking.'/'.$doctor->detail->max_booking;
            }

            $notification = [
                'message' => '<div class="bg-white dark:bg-bgdark border border-success alert text-success" role="alert"> <span class="font-bold">'.$doctor->name.' is Available in '.$filter_date.'</span></div>',
                'data' => UserStaticData::getDoctorTime($doctor->id, '', $filter_date, $request->appointment_id),
                'sectors' => UserStaticData::getDoctorSectors($doctor->id, ''),
                'booking_status' => '<span class="badge border border-primary text-primary">Booking Detail '.$booking_status.'</span>',
            ];

            return response($notification);

        } else {
            $notification = [
                'message' => '<div class="bg-white dark:bg-bgdark border border-danger alert text-danger" role="alert"> <span class="font-bold">Doctor not found</span></div>',
                'data' => null,
                'sectors' => null,
                'booking_status' => null,
            ];

            return response($notification);
        }

    }

    private function checkAppointment($appointment_date, $appointment_id, $doctor)
    {
        $start_time = Carbon::parse($appointment_date);
        $end_time = Carbon::parse($appointment_date)->addMinutes(env('BOOKING_GAP', 15));
        $doctor = User::with(['detail'])->where('id', $doctor)->first();
        if (! $doctor) {
            return 2;
        }

        $today = $start_time->format('l');
        if (in_array(strtoupper($today), $doctor->detail->weekend)) {

            return 2;

        }
        $check_leave = Leave::where('user_id', $doctor->id)->where('start_date', '<=', $start_time->format('Y-m-d'))->where('end_date', '>=', $start_time->format('Y-m-d'))->first();
        if ($check_leave) {

            return 2;
        }

        return Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $start_time->format('Y-m-d'))
            ->count();

    }

    public function approve(Appointment $appointment, $type): RedirectResponse
    {
        $appointment->update(['status' => $type]);
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.appointments.index')->with($notification);

    }

    public function cancel(Appointment $appointment, Request $request): RedirectResponse
    {
        $this->validate($request, ['reason' => ['required', 'string'], 'cancel_by' => ['required', 'string']]);
        $appointment->update(['status' => '2', 'remarks' => $request->reason, 'cancel_by' => $request->cancel_by]);
        $notification = Str::toastMsg(config('custom.msg.update'), 'success');

        return redirect()->route('admin.appointments.index')->with($notification);

    }

    public function doctorAppointment(User $doctor, Request $request): View|RedirectResponse
    {
        $authUser = $request->user();
        if (! $authUser->can('view appointment')) {
            $notification = Str::toastMsg('You do not have permission to view appointment!', 'warning');

            return redirect()->route('admin.dashboard')->with($notification);
        }
        $doctor->load(['detail', 'shifts']);
        $appointment_hours = [];
        $filter_date = date('Y-m-d');
        if ($request->filter_date) {
            $filter_date = $request->filter_date;
        }
        $nepali_date = $this->convertEnglishDateToPlainNepaliDate($filter_date);
        $today = Carbon::parse($filter_date)->format('l');
        if (in_array(strtoupper($today), $doctor->detail->weekend)) {
            $notification = Str::toastMsg($doctor->name.' is on weekend on '.$filter_date, 'warning');

            return redirect()->route('admin.doctors.index')->with($notification);

        }
        $check_leave = Leave::where('user_id', $doctor->id)->where('start_date', '<=', $filter_date)->where('end_date', '>=', $filter_date)->first();
        if ($check_leave) {
            $notification = Str::toastMsg($doctor->name.' is on leave at '.$filter_date, 'danger');

            return redirect()->route('admin.doctors.index')->with($notification);
        }
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $filter_date)
            ->pluck('appointment_date')->toArray();
        $total_booking = count($appointments);

        foreach ($doctor->shifts as $shift) {
            $times = \Carbon\CarbonPeriod::since($filter_date.' '.$shift->start_time)->hours(1)->until($filter_date.' '.$shift->end_time)->toArray();

            foreach ($times as $key => $value) {
                $b_date = $value->format('Y-m-d H:i:s');
                $l_date = $value->addMinutes(45)->format('Y-m-d H:i:s');

                $iTimes = \Carbon\CarbonPeriod::since($b_date)->minutes(15)->until($l_date)->toArray();
                $appointment_times = [];
                foreach ($iTimes as $key => $itime) {
                    $a_date = $itime->format('Y-m-d H:i');
                    $check_date = $itime->format('Y-m-d H:i:s');
                    $booked = null;
                    if (in_array($check_date, $appointments)) {
                        $booked = true;
                    }
                    $appointment_times[] = [
                        'time' => $a_date,
                        'status' => $booked,
                    ];
                }
                $appointment_hours[] = [
                    'hour' => $value->format('H'),
                    'times' => $appointment_times,
                ];

            }

        }

        $provinces = Province::pluck('id', 'title');

        $departments = $doctor->detail->sector ?? [];

        return view('admin.pages.appointments.calendar', compact('appointment_hours', 'doctor', 'filter_date', 'provinces', 'nepali_date', 'departments', 'total_booking'));
    }

    public function getDisease()
    {
        $datas = Disease::groupBy('name')->orderBy('name', 'asc')->pluck('name')->toArray();

        // $datas = ['Purna','Kathmandu','Bhak'];
        return response()->json($datas);
    }

    public function getDistrict(Request $request)
    {
        $this->validate($request, ['province_id' => ['required', 'integer']]);

        return UserStaticData::getDistrict($request->province_id, '');
    }

    public function getMunicipality(Request $request)
    {
        $this->validate($request, ['district_id' => ['required', 'integer']]);

        return UserStaticData::getMunicipality($request->district_id, '');
    }

    public function reschedule(Appointment $appointment, Request $request)
    {
        $this->validate($request, [
            'reschedule_type' => ['required', 'string'],
            'nepali_date' => ['required', 'date'],
            'appointment_date' => ['required', 'date', 'date_format:Y-m-d H:i'],
        ]);

        $new_appointment = Appointment::create([
            'doctor_id' => $appointment->doctor_id,
            'booked_by' => auth()->user()->id,
            'appointment_date' => $request->appointment_date,
            'name' => $appointment->name,
            'age' => $appointment->age,
            'gender' => $appointment->doctor_id,
            'address' => $appointment->address,
            'contact_number' => $appointment->contact_number,
            'payment_mode' => $appointment->payment_mode,
            'visited_type' => $appointment->visited_type,
            'sector' => $appointment->sector,
            'province_id' => $appointment->province_id,
            'district_id' => $appointment->district_id,
            'municipality_id' => $appointment->municipality_id,
            'ward_no' => $appointment->ward_no,
            'house_no' => $appointment->house_no,
            'nepali_date' => $request->nepali_date,

        ]);

        $update_data = [
            'reschedule_type' => $request->reschedule_type,
            'children' => $new_appointment->id,
        ];

        $appointment->update($update_data);
        $notification = Str::toastMsg('Appointment rescheduled successfully', 'success');

        return redirect()->route('admin.appointments.index')->with($notification);
    }

    public function exportAppointment(Request $request)
    {
        $this->validate($request, [
            'from' => ['required', 'date', 'date_format:Y-m-d'],
            'to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);
        $writer = SimpleExcelWriter::streamDownload('appointments.csv');
        $writer->addHeader(['Doctor', 'booked_by', 'appointment_date', 'Disease', 'name', 'age', 'gender', 'contact_number', 'payment_mode', 'status', 'visited_type',
            'sector', 'province', 'district', 'municipality', 'ward_no', 'house_no', 'nepali_date', 'remarks', 'cancel_by']);

        $appointments = Appointment::with(['doctor:id,name', 'province:id,title', 'district:id,title', 'municipality:id,name', 'user:id,name'])
            ->whereDate('appointment_date', '>=', $request->from)
            ->whereDate('appointment_date', '<=', $request->to)
            ->get();
        $i = 0;
        foreach ($appointments->lazy(500) as $appointment) {

            $rows = [
                $appointment->doctor->name ?? '',
                $appointment->user->name ?? '',
                $appointment->appointment_date,
                $appointment->disease_titles,
                $appointment->name,
                $appointment->age,
                $appointment->gender,
                $appointment->contact_number,
                $appointment->payment_mode,
                $appointment->status_title,
                $appointment->visited_type,
                $appointment->sector,
                $appointment->province->title ?? '',
                $appointment->district->title ?? '',
                $appointment->municipality->name ?? '',
                $appointment->ward_no,
                $appointment->house_no,
                $appointment->nepali_date,
                $appointment->remarks,
                $appointment->cancel_by,
            ];

            $writer->addRow($rows);

            if ($i % 300 === 0) {
                flush(); // Flush the buffer every 1000 rows
            }
            $i++;
        }

        return $writer->toBrowser();
    }
}
