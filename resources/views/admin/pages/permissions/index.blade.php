@extends('layouts.admin')
@section('title')
	Create {{ Str::headline(request()->segment(2)) }}
@stop
@section('content')

<div class="block justify-between page-header sm:flex">
    <div>
        <h3 class="text-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white text-2xl font-medium">Create {{ Str::headline(request()->segment(2)) }}</h3>
    </div>
    <ol class="flex items-center whitespace-nowrap min-w-0">
        <li class="text-sm">
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.permissions.index') }}">
                                    {{ Str::headline(request()->segment(2)) }} <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
            </a>
        </li>
        <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Create {{ Str::headline(request()->segment(2)) }}</li>
    </ol>
</div>
<div class="col-span-12">
    <div class="box">
        <div class="box-header flex justify-between">
            <div class="box-title my-auto"> {{ Str::headline(request()->segment(2)) }} </div>
            @if(auth()->user()->can('create permission'))
            <div class="block ltr:ml-auto rtl:mr-auto my-auto">
            <a href="{{ route('admin.permissions.create') }}"  class="text-xs ti-btn ti-btn-primary"> Create {{ Str::headline(request()->segment(2)) }} <i class="ti ti-send"></i></a>
            </div>
            @endif
        </div>
        <div class="box-body widget-table">
            <div class="table-bordered rounded-sm overflow-auto">
                <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th></th>
                            <th><input type="text" id="name" value="{{ request('name') }}" class="ti-form-input" placeholder="name"></th>
                            <th> <button type="button" class="ti-btn ti-btn-success" onclick="filterData()"><i class="ti ti-filter"></i> Filter </button> </th>
                        </tr>
                        @forelse($permissions as $key => $permission)
                            <tr id="row_{{$key}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    @if(auth()->user()->can('edit permission'))
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="ti-btn ti-btn-primary" title="Edit"><i class="ti ti-pencil"></i></a>
                                    @endif
                                    @if(auth()->user()->can('delete permission'))
                                    <button  type="button"  onclick="confirmDelete('{{ route('admin.permissions.destroy', $permission->id) }}', {{$key}},'{{csrf_token()}}')" class="ti-btn ti-btn-danger" title="Delete"><i class="ti ti-trash"></i></button>
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
            {{ $permissions->links() }}
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('assets/js/ajax-delete.js')}}"></script>
    <script>

            function filterData()
            {
                var url = '{{ request()->url() }}'+ '?';
                var title = $('#title').val();
                url += filterNullable('title',title);
                location = url;
            }

            function filterNullable(key,value)
            {
              if(value != '') {
                return '&'+ key+ '='+value;
              }

              return '';
            }
        </script>
@endsection
