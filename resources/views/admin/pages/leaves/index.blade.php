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
                    href="{{ route('admin.leaves.index') }}">
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
                @can('create leave')
                    <div class="block ltr:ml-auto rtl:mr-auto my-auto">
                        <a href="{{ route('admin.leaves.create') }}" class="text-xs ti-btn ti-btn-primary"> Create
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
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th></th>
                                <th>
                                    <select class="py-2 px-3 ti-form-input" id="user" onchange="filterData()">
                                        <option value="">Select Doctor</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == request('user'))>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th><input type="text" id="start_date" value="{{ request('start_date') }}"
                                        class="py-2 px-3 ti-form-input datepicker" placeholder="start_date"></th>
                                <th><input type="text" id="end_date" value="{{ request('end_date') }}"
                                        class="py-2 px-3 ti-form-input datepicker" placeholder="end_date"></th>
                                <th> <button type="button" class="py-1 px-3 ti-btn ti-btn-success"
                                        onclick="filterData()"><i class="ti ti-filter"></i> Filter </button> </th>
                            </tr>
                            @forelse($leaves as $key => $leave)
                                <tr id="row_{{ $key }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $leave->user->name ?? '' }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>
                                        @can('edit leave')
                                            <a href="{{ route('admin.leaves.edit', $leave->id) }}"
                                                class="ti-btn ti-btn-primary" title="Edit"><i class="ti ti-pencil"></i></a>
                                        @endcan
                                        @can('delete leave')
                                            <button type="button"
                                                onclick="confirmDelete('{{ route('admin.leaves.destroy', $leave->id) }}', {{ $key }},'{{ csrf_token() }}')"
                                                class="ti-btn ti-btn-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                        @endcan
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
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}">

@endsection
@section('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-delete.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        function filterData() {
            var url = '{{ request()->url() }}' + '?';
            var user = $('#user').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            url += filterNullable('user', user);
            url += filterNullable('start_date', start_date);
            url += filterNullable('end_date', end_date);
            location = url;
        }

        function filterNullable(key, value) {
            if (value != '') {
                return '&' + key + '=' + value;
            }

            return '';
        }

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
    </script>
@endsection
