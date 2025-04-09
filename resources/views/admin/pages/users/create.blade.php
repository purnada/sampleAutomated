@extends('layouts.admin')
@section('title')
    Create {{ Str::headline(request()->segment(2)) }}
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
                    href="{{ route('admin.users.index') }}">
                    {{ Str::headline(request()->segment(2)) }} <i
                        class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Create
                {{ Str::headline(request()->segment(2)) }}</li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="box-header">
                    <h5 class="box-title">{{ Str::headline(request()->segment(2)) }}</h5>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="grid lg:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Full Name</label>
                                <input id="name" type="text" name="name"
                                    class="my-auto ti-form-input @error('name') border-red-500 @enderror" placeholder="name"
                                    value="{{ old('name') }}" />
                                @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Email</label>
                                <input id="email" type="text" name="email"
                                    class="my-auto ti-form-input @error('email') border-red-500 @enderror"
                                    placeholder="user@domain.com" value="{{ old('email') }}" />
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Password</label>
                                <input id="password" type="password" name="password"
                                    class="my-auto ti-form-input @error('password') border-red-500 @enderror"
                                    placeholder="password" value="{{ old('password') }}" />
                                @error('password')
                                    <span class=" text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="my-auto ti-form-input @error('password_confirmation') border-red-500 @enderror"
                                    value="{{ old('password_confirmation') }}" />
                                @error('password_confirmation')
                                    <span class=" text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Role</label>
                                <select class="ti-form-select  @error('role') border-red-500 @enderror" id="role"
                                    name="role">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @selected($role == old('role'))>
                                            {{ $role }}</option>
                                    @endforeach

                                </select>
                                @error('role')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Permission</label>
                                <select class="ti-form-select  @error('permission') border-red-500 @enderror"
                                    id="permissions" name="permission[]" multiple>
                                    @foreach ($permissions as $permi)
                                        <option value="{{ $permi }}" @selected(in_array($permi, old('permission', [])))>
                                            {{ $permi }}</option>
                                    @endforeach

                                </select>
                                @error('permission.*')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-2 doctor">
                                <label class="ti-form-label mb-0">Specialized Department</label>
                                <select class="ti-form-select  @error('sectors') border-red-500 @enderror" id="sectors"
                                    name="sectors[]" multiple>
                                    @foreach ($sectors as $sector)
                                        <option value="{{ $sector }}" @selected(in_array($sector, old('sectors', [])))>
                                            {{ $sector }}</option>
                                    @endforeach

                                </select>
                                @error('sectors.*')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2 doctor">
                                <label class="ti-form-label mb-0">Weekend</label>
                                <select class="ti-form-select  @error('weekends') border-red-500 @enderror" id="weekends"
                                    name="weekends[]" multiple>
                                    @foreach ($weekends as $weekend)
                                        <option value="{{ $weekend }}" @selected(in_array($weekend, old('weekends', [])))>
                                            {{ $weekend }}</option>
                                    @endforeach

                                </select>
                                @error('weekends.*')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2 doctor">
                                <label class="ti-form-label mb-0">Job</label>
                                <select class="ti-form-select  @error('job') border-red-500 @enderror" name="job">
                                    <option value="Full Time" @selected(old('job') == 'Full Time')>Full Time</option>
                                    <option value="Part Time" @selected(old('job') == 'Part Time')>Part Time</option>
                                </select>
                                @error('job')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="space-y-2 doctor">
                                <label class="ti-form-label mb-0">Contact No.</label>
                                <input id="contact_number" type="text" name="contact_number"
                                    class="my-auto ti-form-input @error('contact_number') border-red-500 @enderror"
                                    placeholder="contact_number" value="{{ old('contact_number') }}" />
                                @error('contact_number')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-2 doctor">
                                <label class="ti-form-label mb-0">Maximum Booking</label>
                                <input id="max_booking" type="text" name="max_booking"
                                    class="my-auto ti-form-input @error('max_booking') border-red-500 @enderror"
                                    placeholder="max_booking"
                                    value="{{ old('max_booking') }}" />
                                @error('max_booking')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="space-y-2">
                                <label class="ti-form-label mb-0">Image</label>
                                <input id="image" type="file" name="image"
                                    class="my-auto ti-form-input @error('image') border-red-500 @enderror"
                                    placeholder="Image" value="{{ old('image') }}" />
                                @error('image')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="table-bordered rounded-sm overflow-auto doctor">
                            <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="shifts">
                                    <tr id="tr_0">
                                        <td><input type="text" name="shift[0][start_time]"class="my-auto ti-form-input time "/></td>
                                        <td><input type="text" name="shift[0][end_time]"class="my-auto ti-form-input time "/></td>

                                        <td>
                                            <button type="button" onclick="$('#tr_0').remove()" class="ti-btn ti-btn-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" onclick="addRow()" class="ti-btn ti-btn-success" title="Delete">Add More</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/timepicker/jquery.timepicker.css') }}">

    <style>
        .doctor {
            display: none;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/libs/timepicker/jquery.timepicker.js') }}"></script>
    <script>
        (function() {


            /* multi select with remove button */
            const multipleCancelButton = new Choices(
                '#permissions', {
                    allowHTML: true,
                    removeItemButton: true,
                }
            );
            const multipleWeekends = new Choices(
                '#weekends', {
                    allowHTML: true,
                    removeItemButton: true,
                }
            );





        })();
        $(document).on('change', '#role', function() {
            var value = $(this).val();

            if (value == "Doctor") {
                $('.doctor').fadeIn();
                const multipleCancelButton = new Choices(
                    '#sectors', {
                        allowHTML: true,
                        removeItemButton: true,
                    }

                );
                const multipleWeekends = new Choices(
                    '#weekends', {
                        allowHTML: true,
                        removeItemButton: true,
                    }

                );

                $('.time').timepicker({
                    'showDuration': true,
                    'timeFormat': 'H:i:s',
                });
            } else {
                $('.doctor').fadeOut();
            }
        })
    </script>
    @if (old('role') == 'Doctor')
        <script>
            $('.doctor').fadeIn();
            const multipleCancelButton = new Choices(
                '#sectors', {
                    allowHTML: true,
                    removeItemButton: true,
                }

            );
            const multipleWeekends = new Choices(
                '#weekends', {
                    allowHTML: true,
                    removeItemButton: true,
                }

            );

            $('.time').timepicker({
                'showDuration': true,
                'timeFormat': 'H:i:s',
            });
        </script>
    @endif

    <script>
        var i = 1;
        function addRow()
        {
            var html = '<tr id="tr_'+i+'"><td><input type="text" name="shift['+i+'][start_time]"class="my-auto ti-form-input time "/></td>';
                html += '<td><input type="text" name="shift['+i+'][end_time]"class="my-auto ti-form-input time "/></td>';
                html += '<td><button type="button" onclick="$(\'#tr_' + i + '\').remove()" class="ti-btn ti-btn-danger" title="Delete"><i class="ti ti-trash"></i></button> </td></tr>';
                $('#shifts').append(html);
                i++;
        }
    </script>

@endsection
