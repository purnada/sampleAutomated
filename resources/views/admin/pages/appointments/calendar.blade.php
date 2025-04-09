@extends('layouts.admin')
@section('title')
    {{ $doctor->name }}'s Appointments
@stop
@section('content')

    <div class="block justify-between page-header sm:flex">
        <div>
            <h3 class="text-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white text-2xl font-medium">
                {{ $doctor->name }}'s Appointments</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-sm">
                <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate"
                    href="{{ route('admin.appointments.index') }}">
                    Appointments <i
                        class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">{{ $doctor->name }}'s
                Appointments</li>
        </ol>
    </div>
    <div class="col-span-12">
        <div class="box">
            <div class="box-header flex justify-between">
                <div class="box-title my-auto"> {{ $doctor->name }}'s Appointments </div>
                <div class="block my-auto">
                    @if($doctor->detail->max_booking > 0)
                    <strong>Booking Status: </strong><strong>{{  $total_booking .'/'.$doctor->detail->max_booking }}</strong>
                    @endif
                </div>
                <div class="block ltr:ml-auto rtl:mr-auto my-auto">

                    <input type="text" class="ti-form-input datepicker" id="appointment_date"
                        value="{{ $filter_date }}">
                </div>

            </div>
            <div class="box-body widget-table">
                <div class="table-bordered rounded-sm overflow-auto">
                    <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                        <thead>
                            <tr>
                                @foreach ($appointment_hours as $hour)
                                    <th scope="col" class="text-center">{{ $hour['hour'] }}</th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                @foreach ($appointment_hours as $hour)
                                    <td scope="col">
                                        <div class="max-w-screen-lg mx-auto">
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach ($hour['times'] as $time)
                                                    <div @if (!$time['status']) onclick="bookAppointment('{{ $time['time'] }}')" @endif
                                                        class="bookingbox w-full h-0 shadow-lg aspect-w-1 aspect-h-1 rounded-xl @if ($time['status']) bg-red-300 @else bg-green-300 @endif">
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>

                                    </td>
                                @endforeach

                            </tr>

                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
    <button type="button" class="hs-dropdown-toggle ti-btn ti-btn-primary hidden" id="modal_button"
        data-hs-overlay="#hs-medium-modal">Medium</button>

    <div id="hs-medium-modal" class="hs-overlay hidden ti-modal">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out md:!max-w-2xl md:w-full m-3 md:mx-auto">
            <div class="ti-modal-content">
                <div class="ti-modal-header">
                    <h3 class="ti-modal-title">Modal title</h3>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#hs-medium-modal">
                        <span class="sr-only">Close</span>
                        <svg class="w-3.5 h-3.5" width="8" height="8" viewBox="0 0 8 8" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div class="ti-modal-body">
                    <form method="POST" action="{{ route('admin.appointments.store') }}" enctype="multipart/form-data"
                        id="appointment_form">
                        @csrf

                        <input type="hidden" name="appointment_date" id="appointment_date_doctor">
                        <input type="hidden" name="page" value="calendar">
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                        <input type="hidden" name="nepali_date" value="{{ $nepali_date }}" id="nepali_date_doctor">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Department</label>
                                <select class="ti-form-select @error('sector') border-red-500 @enderror" name="sector"
                                    required id="sector_id">
                                    <option value="">Select Sector</option>

                                        @foreach ($departments as $department)
                                            <option value="{{ $department }}">{{ $department }}</option>
                                        @endforeach


                                </select>


                                @error('sector')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Desieses</label>
                                <input id="disease" name="disease" type="text"
                                    class="my-auto ti-form-input @error('disease') border-red-500 @enderror"
                                    data-role="tagsinput" value="{{ old('disease') }}" />
                                @error('disease')
                                    <span class=" text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Name</label>
                                <input id="name" name="name" type="text"
                                    class="my-auto ti-form-input @error('name') border-red-500 @enderror" placeholder="Name"
                                    value="{{ old('name') }}" />
                                @error('name')
                                    <span class=" text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Age</label>
                                <input id="age" name="age" type="text"
                                    class="my-auto ti-form-input @error('age') border-red-500 @enderror" placeholder="Age"
                                    value="{{ old('age') }}" />
                                @error('age')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Gender</label>
                                <select class="ti-form-select @error('gender') border-red-500 @enderror" name="gender">
                                    <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                                    <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                                    <option value="Others" @selected(old('gender') == 'Others')>Others</option>
                                </select>

                                @error('gender')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Province</label>
                                <select class="ti-form-select @error('province_id') border-red-500 @enderror"
                                    name="province_id" id="province_id">
                                    <option value="">Select Province</option>
                                    @foreach ($provinces as $name => $id)
                                        <option value="{{ $id }}" @selected($id == old('province_id'))>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>


                                @error('province_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">District</label>
                                <select class="ti-form-select @error('district_id') border-red-500 @enderror"
                                    name="district_id" id="district_id">
                                    <option value="">Select Province</option>
                                    {!! \App\Library\UserStaticData::getDistrict(old('province_id'), old('district_id')) !!}
                                </select>


                                @error('district_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Municipality</label>
                                <select class="ti-form-select @error('municipality_id') border-red-500 @enderror"
                                    name="municipality_id" id="municipality_id">
                                    <option value="">Select Province</option>
                                    {!! \App\Library\UserStaticData::getMunicipality(old('district_id'), old('municipality_id')) !!}
                                </select>


                                @error('municipality_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Ward No.</label>
                                <input id="ward_no" name="ward_no" type="text"
                                    class="my-auto ti-form-input @error('ward_no') border-red-500 @enderror"
                                    placeholder="ward_no" value="{{ old('ward_no') }}" />
                                @error('ward_no')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">House No</label>
                                <input id="house_no" name="house_no" type="text"
                                    class="my-auto ti-form-input @error('house_no') border-red-500 @enderror"
                                    placeholder="house_no" value="{{ old('house_no') }}" />
                                @error('house_no')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Contact Number</label>
                                <input id="contact_number" name="contact_number" type="text"
                                    class="my-auto ti-form-input @error('contact_number') border-red-500 @enderror"
                                    placeholder="contact_number" value="{{ old('contact_number') }}" />
                                @error('contact_number')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Payment Mode</label>
                                <select class="ti-form-select @error('payment_mode') border-red-500 @enderror"
                                    name="payment_mode">
                                    <option value="Online" @selected(old('payment_mode') == 'Online')>Online</option>
                                    <option value="COD" @selected(old('payment_mode') == 'COD')>COD</option>
                                </select>

                                @error('payment_mode')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Visited Type</label>
                                <select class="ti-form-select @error('visited_type') border-red-500 @enderror"
                                    name="visited_type">
                                    <option value="New" @selected(old('visited_type') == 'New')>New Patient</option>
                                    <option value="Existing" @selected(old('visited_type') == 'Existing')>Existing Patient</option>
                                </select>

                                @error('visited_type')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>


                        <button value="submit" type="submit"
                            class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}">
    <style>
        table .text-center {
            text-align: center !important
        }

        .bg-green-300 {
            --tw-bg-opacity: 1;
            background-color: rgba(110, 231, 183, var(--tw-bg-opacity));
        }

        .bg-red-300 {
            --tw-bg-opacity: 1;
            background-color: rgba(252, 165, 165, var(--tw-bg-opacity));
        }

        .bookingbox {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/libs/tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/tagsinput/app.css') }}">
    <style>
        .label {
            padding-left: 0.625rem !important;
            padding-right: 0.625rem !important;
            display: inline-block;
            vertical-align: middle;
            border-radius: 20px;
            padding: 4px 10px;
            padding-right: 10px;
            padding-left: 10px;
            font-size: 12px;
            font-weight: 500;
            margin-right: 3.75px;
            margin-bottom: 3.75px;
            background-color: #00bcd4;
            border: 1px solid #00a5bb;

            color: #fff;
            word-break: break-all;
            box-sizing: border-box;
            border-color: rgb(var(--color-primary)) !important;
            background-color: rgb(var(--color-primary)) !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-delete.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        function filterData(filter_date) {
            var url = '{{ request()->url() }}' + '?';
            url += filterNullable('filter_date', filter_date);
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
                showAnim: "fold",
                onSelect: function() {
                    var date = $(this).val();
                    filterData(date);

                }
            });

        })

        function bookAppointment(date) {
            $('#appointment_date_doctor').val(date);
            $('#modal_button').trigger('click');
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

    <script src="{{ asset('assets/libs/tagsinput/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('assets/libs/tagsinput/app.js') }}"></script>
    <script type="text/javascript">
        var citynames = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: "{{ route('admin.getDisease') }}",
                // url: '{{ url('assets/countries.json') }}',
                filter: function(list) {
                    return $.map(list, function(cityname) {

                        return {
                            name: cityname
                        };
                    });
                }
            }
        });
        citynames.initialize();



        $('#disease').tagsinput({
            typeaheadjs: {
                name: 'citynames',
                displayKey: 'name',
                valueKey: 'name',
                source: citynames.ttAdapter()
            }
        });

        $("#province_id").on("change", function() {

            var province_id = $(this).val();

            var token = '{{ csrf_token() }}';

            if (province_id != '') {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.getDistrict') }}",
                    data: {
                        _token: token,
                        province_id: province_id,

                    },
                    success: function(data) {
                        $('#district_id').html(data);
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
        });

        $("#district_id").on("change", function() {

            var district_id = $(this).val();

            var token = '{{ csrf_token() }}';

            if (district_id != '') {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.getMunicipality') }}",
                    data: {
                        _token: token,
                        district_id: district_id,

                    },
                    success: function(data) {
                        $('#municipality_id').html(data);
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
        });
    </script>

    <script>
        let val = document.getElementById("appointment_form");
        val.onkeypress = function(key) {
            var btn = 0 || key.keyCode || key.charCode;
            if (btn == 13) {

                key.preventDefault();
            }
        }
    </script>
@endsection
