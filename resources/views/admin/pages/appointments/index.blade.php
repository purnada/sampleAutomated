@extends('layouts.admin')
@section('title')
    Appointments
@stop
@section('content')

    <div class="block justify-between page-header sm:flex">
        <div>
            <h3 class="text-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white text-2xl font-medium">Create
                {{ Str::headline(request()->segment(2)) }}</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-sm">
                <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate"
                    href="{{ route('admin.appointments.index') }}">
                    {{ Str::headline(request()->segment(2)) }} <i
                        class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Create
                {{ Str::headline(request()->segment(2)) }}</li>
        </ol>
    </div>
    <div class="col-span-12">
        <div class="box">
            <div class="box-header flex justify-between">
                <div class="box-title my-auto"> {{ Str::headline(request()->segment(2)) }} </div>
                <div class="block ltr:ml-auto rtl:mr-auto my-auto">
                    @can('export appointment')
                    <button type="button" class="hs-dropdown-toggle text-xs ti-btn ti-btn-success"
                                                    data-hs-overlay="#exportModal"><i
                                                        class="ti ti-cloud-download"></i> Export Data </button>
                                                        @endif

                </div>
                @can('create appointment')
                    <div class="block ltr:ml-auto rtl:mr-auto my-auto">
                        <a href="{{ route('admin.appointments.create') }}" class="text-xs ti-btn ti-btn-primary"> Create
                            {{ Str::headline(request()->segment(2)) }} <i class="ti ti-send"></i></a>
                    </div>
                @endcan
            </div>
            <div class="box-body widget-table">
                <div class="table-bordered rounded-sm overflow-auto">
                    <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Patient</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Date</th>
                                <th scope="col">Booked By</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th></th>
                                <th>
                                    @if ($user_role != 'Doctor')
                                        <select class="ti-form-input" id="doctor">
                                            <option value="">Select Doctor</option>
                                            @foreach ($doctors as $id => $name)
                                                <option value="{{ $id }}" @selected($id == request('doctor'))>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </th>

                                <th><input type="text" id="name" value="{{ request('name') }}"
                                        class="py-2 px-3 ti-form-input" placeholder="name"></th>
                                <th><input type="text" id="contact_number" value="{{ request('contact_number') }}"
                                        class="py-2 px-3 ti-form-input" placeholder="contact_number"></th>
                                <th><input type="text" id="appointment_date" value="{{ request('appointment_date') }}"
                                        class="py-2 px-3 ti-form-input datepicker" placeholder="appointment_date"></th>
                                <th></th>
                                <th>
                                    <select class="ti-form-input" id="status">
                                        <option value="">Select Status</option>
                                        @foreach ($status as $stat)
                                            <option value="{{ $stat['value'] }}" @selected(request('status') == $stat['value'])>
                                                {{ $stat['title'] }}</option>
                                        @endforeach

                                    </select>
                                </th>
                                <th> <button type="button" class="py-1 px-3 ti-btn ti-btn-success"
                                        onclick="filterData()"><i class="ti ti-filter"></i> Filter </button> </th>
                            </tr>
                            @forelse($appointments as $key => $appointment)
                                <tr id="row_{{ $key }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $appointment->doctor->name ?? '' }}</td>
                                    <td>{{ $appointment->name }}</td>
                                    <td>{{ $appointment->contact_number }}</td>
                                    <td>{{ $appointment->appointment_date }} ({{ $appointment->nepali_date }})</td>
                                    <td>{{ $appointment->user->name ?? '' }}</td>
                                    <td>{!! $appointment->status_span !!}
                                        @if ($appointment->reschedule_type)
                                            <span class="badge border border-primary text-primary">
                                                {{ $appointment->reschedule_type }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hs-dropdown ti-dropdown">
                                            <button id="hs-dropdown-default" type="button"
                                                class="hs-dropdown-toggle ti-dropdown-toggle">
                                                Actions
                                                <svg class="hs-dropdown-open:rotate-180 ti-dropdown-caret" width="16"
                                                    height="16" viewBox="0 0 16 16" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                                </svg>
                                            </button>
                                            <div class="hs-dropdown-menu ti-dropdown-menu"
                                                aria-labelledby="hs-dropdown-default">
                                                @if ($appointment->status != '3')
                                                    @can('edit appointment')
                                                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                                            class="ti-dropdown-item" title="Edit"><i class="ti ti-pencil"></i>
                                                            Edit</a>
                                                    @endcan
                                                    @can('delete appointment')
                                                        <button type="button"
                                                            onclick="confirmDelete('{{ route('admin.appointments.destroy', $appointment->id) }}', {{ $key }},'{{ csrf_token() }}')"
                                                            class="ti-dropdown-item" title="Delete"><i class="ti ti-trash"></i>
                                                            Delete</button>
                                                    @endcan
                                                @endif
                                                <button type="button" class="hs-dropdown-toggle ti-dropdown-item"
                                                    data-hs-overlay="#appointmentModal{{ $appointment->id }}"><i
                                                        class="ti ti-eye"></i> Detail
                                                </button>
                                                @can('approve appointment')
                                                    @if ($appointment->status == '1' & $appointment->status != '3')
                                                        <button type="button"
                                                            onclick="confirmApprove('{{ route('admin.appointment.approve', [$appointment->id, 3]) }}')"
                                                            class="ti-dropdown-item" title="Arrived"><i
                                                                class="ti ti-thumb-up"></i> Fully Completed</button>
                                                    @endif

                                                    @if ($appointment->status != '3')
                                                    @if ($appointment->status != '1')
                                                        <button type="button"
                                                            onclick="confirmApprove('{{ route('admin.appointment.approve', [$appointment->id, 1]) }}')"
                                                            class="ti-dropdown-item" title="Arrived"><i
                                                                class="ti ti-thumb-up"></i> Approve</button>
                                                    @endif
                                                        @if ($appointment->status != '2')
                                                            <button type="button"
                                                                onclick="confirmCancel('{{ route('admin.appointment.cancel', $appointment->id) }}')"
                                                                class="ti-dropdown-item" title="Canceled"><i
                                                                    class="ti ti-thumb-down"></i> Cancel</button>
                                                        @endif
                                                        @if (!$appointment->children)
                                                            <button type="button"
                                                                onclick="confirmReschedule('{{ route('admin.appointment.reschedule', $appointment->id) }}','{{ $appointment->nepali_date }}','{{ $appointment->doctor_id }}')"
                                                                class="ti-dropdown-item" title="Canceled"><i
                                                                    class="ti ti-arrow-back-up"></i> Reschedule</button>
                                                        @endif
                                                    @endif
                                                @endcan



                                            </div>
                                        </div>




                                        <div id="appointmentModal{{ $appointment->id }}"
                                            class="hs-overlay ti-modal hidden">
                                            <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
                                                <div class="ti-modal-content">
                                                    <div class="ti-modal-header">
                                                        <h3 class="ti-modal-title"> Appointment Detail </h3>
                                                        <button type="button"
                                                            class="hs-dropdown-toggle ti-modal-close-btn"
                                                            data-hs-overlay="#appointmentModal{{ $appointment->id }}">
                                                            <span class="sr-only">Close</span> <svg class="w-3.5 h-3.5"
                                                                width="8" height="8" viewBox="0 0 8 8"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                                                                    fill="currentColor"></path>
                                                            </svg> </button>
                                                    </div>
                                                    <div class="ti-modal-body">
                                                        <div class="table-bordered rounded-sm overflow-auto">
                                                            <table
                                                                class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                                                                <tr>
                                                                    <td>Doctor:</td>
                                                                    <td><strong>{{ $appointment->doctor->name ?? '' }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Department:</td>
                                                                    <td><strong>{{ $appointment->sector }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Desieses:</td>
                                                                    <td>
                                                                        @foreach ($appointment->diseases as $desies)
                                                                            <span
                                                                                class="badge border border-primary text-primary">{{ $desies->name }}</span>
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Name:</td>
                                                                    <td><strong>{{ $appointment->user->name ?? '' }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contact Number:</td>
                                                                    <td><strong>{{ $appointment->contact_number }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Province:</td>
                                                                    <td><strong>{{ $appointment->province->title ?? '' }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>District:</td>
                                                                    <td><strong>{{ $appointment->district->title ?? '' }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Municipality:</td>
                                                                    <td><strong>{{ $appointment->municipality->name ?? '' }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Ward No:</td>
                                                                    <td><strong>{{ $appointment->ward_no }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>House No:</td>
                                                                    <td><strong>{{ $appointment->house_no }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Gender:</td>
                                                                    <td><strong>{{ $appointment->gender }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Age:</td>
                                                                    <td><strong>{{ $appointment->age }} Years</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Status:</td>
                                                                    <td><strong>{!! $appointment->status_span !!}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Date Time:</td>
                                                                    <td><strong>{{ $appointment->appointment_date }}
                                                                            ({{ $appointment->nepali_date }})
                                                                        </strong>
                                                                    </td>
                                                                </tr>
                                                                @if ($appointment->cancel_by)
                                                                    <tr>
                                                                        <td>Cancel By:</td>
                                                                        <td><strong>{!! $appointment->cancel_by !!}</strong></td>
                                                                    </tr>
                                                                @endif
                                                                @if ($appointment->remarks)
                                                                    <tr>
                                                                        <td>Remarks:</td>
                                                                        <td><strong>{!! $appointment->remarks !!}</strong></td>
                                                                    </tr>
                                                                @endif
                                                                @if ($appointment->reschedule_type)
                                                                    <tr>
                                                                        <td>Reschedule:</td>
                                                                        <td><span
                                                                                class="badge border border-primary text-primary">{{ $appointment->reschedule_type }}</span>

                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                            </table>

                                                        </div>
                                                        @if($appointment->child)
                                                            <p class="mt-10 mb-10"><strong>Next Schedule</strong></p>
                                                            <div class="table-bordered rounded-sm overflow-auto">

                                                                <table
                                                                    class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                                                                    <tr>
                                                                        <td>Doctor:</td>
                                                                        <td><strong>{{ $appointment->child->doctor->name ?? '' }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Department:</td>
                                                                        <td><strong>{{ $appointment->child->sector }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Desieses:</td>
                                                                        <td>
                                                                            @foreach ($appointment->child->diseases as $desies)
                                                                                <span
                                                                                    class="badge border border-primary text-primary">{{ $desies->name }}</span>
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Name:</td>
                                                                        <td><strong>{{ $appointment->child->user->name ?? '' }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Contact Number:</td>
                                                                        <td><strong>{{ $appointment->child->contact_number }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Province:</td>
                                                                        <td><strong>{{ $appointment->child->province->title ?? '' }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>District:</td>
                                                                        <td><strong>{{ $appointment->child->district->title ?? '' }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Municipality:</td>
                                                                        <td><strong>{{ $appointment->child->municipality->name ?? '' }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Ward No:</td>
                                                                        <td><strong>{{ $appointment->child->ward_no }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>House No:</td>
                                                                        <td><strong>{{ $appointment->child->house_no }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gender:</td>
                                                                        <td><strong>{{ $appointment->child->gender }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Age:</td>
                                                                        <td><strong>{{ $appointment->child->age }} Years</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Status:</td>
                                                                        <td><strong>{!! $appointment->child->status_span !!}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Date Time:</td>
                                                                        <td><strong>{{ $appointment->child->appointment_date }}
                                                                                ({{ $appointment->child->nepali_date }})
                                                                            </strong>
                                                                        </td>
                                                                    </tr>
                                                                    @if ($appointment->child->cancel_by)
                                                                        <tr>
                                                                            <td>Cancel By:</td>
                                                                            <td><strong>{!! $appointment->child->cancel_by !!}</strong></td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($appointment->child->remarks)
                                                                        <tr>
                                                                            <td>Remarks:</td>
                                                                            <td><strong>{!! $appointment->child->remarks !!}</strong></td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($appointment->child->reschedule_type)
                                                                        <tr>
                                                                            <td>Reschedule:</td>
                                                                            <td><span
                                                                                    class="badge border border-primary text-primary">{{ $appointment->child->reschedule_type }}</span>

                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                </table>

                                                            </div>
                                                       @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="3">No Record found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
    <div id="hs-reschedule-modal" class="hs-overlay ti-modal hidden">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
            <div class="ti-modal-content">
                <div class="ti-modal-header">
                    <h3 class="ti-modal-title"> Reschedule Appointment </h3>
                    <div class="block ltr:ml-auto rtl:mr-auto my-auto" id="booking_status">

                    </div>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#reschedule">
                        <span class="sr-only">Close</span> <svg class="w-3.5 h-3.5" width="8" height="8"
                            viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                                fill="currentColor"></path>
                        </svg> </button>
                </div>
                <div class="ti-modal-body">
                    <form method="POST" id="reschedule_form" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="re_doctor" value="">
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Reschedule Type</label>
                            <select class="my-auto ti-form-select" name="reschedule_type">
                                <option value="Follow Up">Follow Up</option>
                                <option value="Reschedule">Reschedule</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Appointment Date</label>
                            <input id="appoint_date" type="text" name="nepali_date"
                                class="my-auto ti-form-input @error('nepali_date') border-red-500 @enderror"
                                placeholder="YYYY-MM-DD" value="" />
                            @error('nepali_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <div id="date_message"></div>
                        </div>

                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Time</label>
                            <select class="ti-form-select @error('appointment_date') border-red-500 @enderror"
                                name="appointment_date" id="re_appointment_date">
                                <option value="">Select Time</option>

                            </select>


                            @error('appointment_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <button value="submit" type="submit"
                            class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="hs-cancel-modal" class="hs-overlay ti-modal hidden">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
            <div class="ti-modal-content">
                <div class="ti-modal-header">
                    <h3 class="ti-modal-title"> Cancel Appointment </h3>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#cancel">
                        <span class="sr-only">Close</span> <svg class="w-3.5 h-3.5" width="8" height="8"
                            viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                                fill="currentColor"></path>
                        </svg> </button>
                </div>
                <div class="ti-modal-body">
                    <form method="POST" id="cancel_form" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">cancel By</label>
                            <select class="my-auto ti-form-select" name="cancel_by">
                                <option value="patient">Patient</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Reason</label>
                            <input type="text" name="reason" class="my-auto ti-form-input "
                                value="{{ old('reason') }}" />
                        </div>
                        <button value="submit" type="submit"
                            class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div id="exportModal" class="hs-overlay ti-modal hidden">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
            <div class="ti-modal-content">
                <div class="ti-modal-header">
                    <h3 class="ti-modal-title"> Export Appointment </h3>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#export">
                        <span class="sr-only">Close</span> <svg class="w-3.5 h-3.5" width="8" height="8"
                            viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                                fill="currentColor"></path>
                        </svg> </button>
                </div>
                <div class="ti-modal-body">
                    <form method="POST" id="export_form" action="{{ route('admin.appointment.export') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">From</label>
                            <input type="text" name="from" class="my-auto ti-form-input datepicker"
                                value="{{ old('form') }}" />
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">To</label>
                            <input type="text" name="to" class="my-auto ti-form-input datepicker"
                                value="{{ old('to') }}" />
                        </div>
                        <button value="submit" type="submit"
                            class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <button type="button" class="hs-dropdown-toggle ti-btn ti-btn-primary hidden" id="modal_button"
        data-hs-overlay="#hs-cancel-modal">Medium</button>
    <button type="button" class="hs-dropdown-toggle ti-btn ti-btn-primary hidden" id="reschedule_button"
        data-hs-overlay="#hs-reschedule-modal">Medium</button>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}">
    <link href="{{ asset('assets/libs/nepalidatepicker/css/nepali.datepicker.v3.7.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-delete.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/nepalidatepicker/js/nepali.datepicker.v3.7.min.js') }}" type="text/javascript">
    </script>
    <script>
        function filterData() {
            var url = '{{ request()->url() }}' + '?';
            var name = $('#name').val();
            var contact_number = $('#contact_number').val();
            url += filterNullable('name', name);
            url += filterNullable('contact_number', contact_number);
            @if ($user_role != 'Doctor')
                var doctor = $('#doctor').val();
                url += filterNullable('doctor', doctor);
            @endif
            var appointment_date = $('#appointment_date').val();
            url += filterNullable('appointment_date', appointment_date);
            var status = $('#status').val();
            url += filterNullable('status', status);
            location = url;
        }

        function filterNullable(key, value) {
            if (value != '') {
                return '&' + key + '=' + value;
            }

            return '';
        }
    </script>
    <script>
        $(document).ready(function() {

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showAnim: "fold"
            });

        })
    </script>

    <script>
        function confirmApprove(url) {
            if (confirm("Are you sure you want to confirm apprived this applintment?")) {
                location = url;
            }
        }

        function confirmCancel(url) {
            $('#cancel_form').attr('action', url);
            $('#modal_button').trigger('click');
        }


        function confirmReschedule(url, nepali, doctor) {
            $('#reschedule_form').attr('action', url);
            $('#appoint_date').val(nepali);
            $('#re_doctor').val(doctor);
            $('#reschedule_button').trigger('click');

            var nepaliInput = document.getElementById("appoint_date");
            nepaliInput.nepaliDatePicker({
                onChange: function() {
                    var date = $('#appoint_date').val();
                    var doctor = $('#re_doctor').val();
                    getData(date, doctor);
                }
            });
            getData(nepali, doctor);
        }

        function getData(date_time, doctor) {

            var token = '{{ csrf_token() }}';

            if (date_time != '' || doctor != '') {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.getDates') }}",
                    data: {
                        _token: token,
                        date_time: date_time,
                        doctor: doctor
                    },
                    success: function(data) {
                        $('#date_message').html(data.message);
                        $('#re_appointment_date').html(data.data);

                        $('#booking_status').html(data.booking_status)
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            // Iterate through the errors and display them
                            $.each(errors, function(key, value) {
                                // You can display the error messages however you prefer (e.g., alert, append to HTML, etc.)
                                toastr.error(value[0]);
                                console.log(key + ": " + value[0]);
                            });
                        } else {
                            console.log('Something went wrong.');
                        }
                    }
                });
            }
        }
    </script>
@endsection
