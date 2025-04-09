@extends('layouts.admin')
@section('title')
	Edit {{ Str::headline(request()->segment(2)) }}
@stop
@section('content')

<div class="block justify-between page-header sm:flex">
    <div>
        <h3 class="text-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white text-2xl font-medium">Edit {{ Str::headline(request()->segment(2)) }}</h3>
    </div>
    <ol class="flex items-center whitespace-nowrap min-w-0">
        <li class="text-sm">
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.appointments.index') }}">
                                    {{ Str::headline(request()->segment(2)) }} <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
            </a>
        </li>
        <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Edit {{ Str::headline(request()->segment(2)) }}</li>
    </ol>
</div>
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="box">
            <div class="box-header flex justify-between">
                <div class="box-title my-auto"> {{ Str::headline(request()->segment(2)) }} </div>

                    <div class="block ltr:ml-auto rtl:mr-auto my-auto" id="booking_status">

                    </div>

            </div>

            <div class="box-body">
                <form method="POST" action="{{ route('admin.appointments.update',$appointment->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Doctor</label>
                            <select class="ti-form-select @error('doctor_id') border-red-500 @enderror" name="doctor_id"
                                id="doctor">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected($doctor->id == old('doctor_id',$appointment->doctor_id))>{{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>


                            @error('doctor_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Appointment Date</label>
                            <input id="appoint_date" type="text" name="nepali_date"
                                class="my-auto ti-form-input @error('nepali_date') border-red-500 @enderror"
                                placeholder="YYYY-MM-DD" value="{{ old('nepali_date',$appointment->nepali_date) }}" />
                            @error('nepali_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <div id="date_message"></div>
                        </div>

                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Time</label>
                            <select class="ti-form-select @error('appointment_date') border-red-500 @enderror"
                                name="appointment_date" id="appointment_date">
                                <option value="">Select Time</option>
                                {!! \App\Library\UserStaticData::getDoctorTime(
                                    old('doctor_id',$appointment->doctor_id),
                                    old('appointment_date',$appointment->appointment_date),
                                    old('nepali_date',$appointment->nepali_date),
                                    '',
                                ) !!}
                            </select>


                            @error('appointment_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Department</label>
                            <select class="ti-form-select @error('sector') border-red-500 @enderror" name="sector"
                                id="sector_id">
                                <option value="">Select Sector</option>
                                {!! \App\Library\UserStaticData::getDoctorSectors(old('doctor_id',$appointment->doctor_id), old('sector',$appointment->sector)) !!}
                            </select>


                            @error('sector')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Desieses</label>
                            <input id="disease" name="disease" type="text"
                                class="my-auto ti-form-input @error('disease') border-red-500 @enderror"
                                data-role="tagsinput" value="{{ old('disease',$appointment->disease_titles) }}" />
                            @error('disease')
                                <span class=" text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Patient Name</label>
                            <input id="name" name="name" type="text"
                                class="my-auto ti-form-input @error('name') border-red-500 @enderror" placeholder="Name"
                                value="{{ old('name',$appointment->name) }}" />
                            @error('name')
                                <span class=" text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Age</label>
                            <input id="age" name="age" type="text"
                                class="my-auto ti-form-input @error('age') border-red-500 @enderror" placeholder="Age"
                                value="{{ old('age',$appointment->age) }}" />
                            @error('age')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Gender</label>
                            <select class="ti-form-select @error('gender') border-red-500 @enderror" name="gender">
                                <option value="Male" @selected(old('gender',$appointment->gender) == 'Male')>Male</option>
                                <option value="Female" @selected(old('gender',$appointment->gender) == 'Female')>Female</option>
                                <option value="Others" @selected(old('gender',$appointment->gender) == 'Others')>Others</option>
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
                                    <option value="{{ $id }}" @selected($id == old('province_id',$appointment->province_id))>
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
                                {!! \App\Library\UserStaticData::getDistrict(old('province_id',$appointment->province_id), old('district_id',$appointment->district_id)) !!}
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
                                {!! \App\Library\UserStaticData::getMunicipality(old('district_id',$appointment->district_id), old('municipality_id',$appointment->municipality_id)) !!}
                            </select>


                            @error('municipality_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Ward No.</label>
                            <input id="ward_no" name="ward_no" type="text"
                                class="my-auto ti-form-input @error('ward_no') border-red-500 @enderror"
                                placeholder="ward_no" value="{{ old('ward_no',$appointment->ward_no) }}" />
                            @error('ward_no')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">House No</label>
                            <input id="house_no" name="house_no" type="text"
                                class="my-auto ti-form-input @error('house_no') border-red-500 @enderror"
                                placeholder="house_no" value="{{ old('house_no',$appointment->house_no) }}" />
                            @error('house_no')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Contact Number</label>
                            <input id="contact_number" name="contact_number" type="text"
                                class="my-auto ti-form-input @error('contact_number') border-red-500 @enderror"
                                placeholder="contact_number" value="{{ old('contact_number',$appointment->contact_number) }}" />
                            @error('contact_number')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Payment Mode</label>
                            <select class="ti-form-select @error('payment_mode') border-red-500 @enderror"
                                name="payment_mode">
                                <option value="Online" @selected(old('payment_mode',$appointment->payment_mode) == 'Online')>Online</option>
                                <option value="COD" @selected(old('payment_mode',$appointment->payment_mode) == 'COD')>COD</option>
                            </select>

                            @error('payment_mode')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Visited Type</label>
                            <select class="ti-form-select @error('visited_type') border-red-500 @enderror"
                                name="visited_type">
                                <option value="New" @selected(old('visited_type',$appointment->visited_type) == 'New')>New Patient</option>
                                <option value="Existing" @selected(old('visited_type',$appointment->visited_type) == 'Existing')>Existing Patient</option>
                            </select>

                            @error('visited_type')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                        <button value="submit" type="submit" class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
    <link href="{{ asset('assets/libs/nepalidatepicker/css/nepali.datepicker.v3.7.min.css') }}" rel="stylesheet"
        type="text/css" />
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
    <script src="{{ asset('assets/libs/nepalidatepicker/js/nepali.datepicker.v3.7.min.js') }}" type="text/javascript">
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
                // url: '{{url("assets/countries.json")}}',
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
    </script>
    <script type="text/javascript">
        $('#doctor').on('change', function() {
            var date = $('#appoint_date').val();
            var doctor = $(this).val();
            getData(date, doctor);
        })

        window.onload = function() {

            var nepaliInput = document.getElementById("appoint_date");
            nepaliInput.nepaliDatePicker({
                onChange: function() {
                    var date = $('#appoint_date').val();
                    var doctor = $('#doctor').val();
                    getData(date, doctor);
                }
            });
        };

        function getData(date_time, doctor) {

            var token = '{{ csrf_token() }}';

            if (date_time != '' || doctor != '') {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.getDates') }}",
                    data: {
                        _token: token,
                        date_time: date_time,
                        doctor: doctor,
                        appointment_id: {{ $appointment->id }}
                    },
                    success: function(data) {
                        $('#date_message').html(data.message);
                        $('#appointment_date').html(data.data);
                        $('#sector_id').html(data.sectors);
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
