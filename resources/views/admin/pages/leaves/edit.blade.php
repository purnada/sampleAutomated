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
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.leaves.index') }}">
                                    {{ Str::headline(request()->segment(2)) }} <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
            </a>
        </li>
        <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Edit {{ Str::headline(request()->segment(2)) }}</li>
    </ol>
</div>
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="box">
            <div class="box-header"><h5 class="box-title">{{ Str::headline(request()->segment(2)) }}</h5></div>
            <div class="box-body">
                <form method="POST" action="{{ route('admin.leaves.update',$leave->id) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div class="sm:grid grid-cols-12 gap-x-6">
                        <label class="ti-form-label mb-0 required col-span-3">Doctor</label>
                        <select class="col-span-9 ti-form-input @error('user_id') border-red-500 @enderror" name="user_id">
                            <option value="">Select Doctor</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($user->id == old('user_id',$leave->user_id))>{{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('user_id')
                            <span class="col-span-12 text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:grid grid-cols-12 gap-x-6">
                        <label class="ti-form-label mb-0 required col-span-3">Start Date</label>
                        <input id="start_date" type="text" name="start_date"
                            class="col-span-9 ti-form-input @error('start_date') border-red-500 @enderror datepicker"
                            value="{{ old('start_date',$leave->start_date) }}" />

                        @error('start_date')
                            <span class="col-span-12 text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:grid grid-cols-12 gap-x-6">
                        <label class="ti-form-label mb-0 required col-span-3">End Date</label>
                        <input id="end_date" type="text" name="end_date"
                            class="col-span-9 ti-form-input @error('end_date') border-red-500 @enderror datepicker"
                            value="{{ old('end_date',$leave->end_date) }}" />

                        @error('end_date')
                            <span class="col-span-12 text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <button value="submit" type="submit" class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}">

@endsection
@section('scripts')
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showAnim: "fold",

            });

        })
    </script>
@endsection
