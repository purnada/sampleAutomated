@extends('layouts.admin')
@section('title')
    Doctors
@stop
@section('content')

    <div class="block justify-between page-header sm:flex">
        <div>
            <h3 class="text-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white text-2xl font-medium">
                Doctors</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">

            <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Create
                Doctors</li>
        </ol>
    </div>
    <div class="col-span-12">
        <div class="box">
            <div class="box-header flex justify-between">
                <div class="box-title my-auto"> Doctors </div>

            </div>
            <div class="box-body widget-table">
                <div class="table-bordered rounded-sm overflow-auto">
                    <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Department</th>
                                <th scope="col">Shift</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th></th>

                                <th><input type="text" id="name" value="{{ request('name') }}" class="ti-form-input"
                                        placeholder="name"></th>
                                <th></th>
                                <th></th>
                                <th> <button type="button" class="ti-btn ti-btn-success" onclick="filterData()"><i
                                            class="ti ti-filter"></i> Filter </button> </th>
                            </tr>

                            @forelse($users as $key => $user)
                            @php($user_roles = [])
                                <tr id="row_{{ $key }}">
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->detail->sectors ?? "" }}</td>
                                    <td>{{ $user->detail->start_time ? $user->detail->start_time.' - ' : "" }} {{ $user->detail->end_time ?? "" }}</td>
                                    <td>


                                        @if (auth()->user()->can('view appointment'))

                                                <a href="{{ route('admin.doctor.appointment', $user->id) }}"
                                                    class="ti-btn ti-btn-primary" title="Appointments">Appointments</a>

                                        @endif
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-delete.js') }}"></script>
    <script>
        function filterData() {
            var url = '{{ request()->url() }}' + '?';
            var name = $('#name').val();
            url += filterNullable('name', name);


            location = url;
        }

        function filterNullable(key, value) {
            if (value != '') {
                return '&' + key + '=' + value;
            }

            return '';
        }
    </script>
@endsection
