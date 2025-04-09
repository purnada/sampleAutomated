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
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.roles.index') }}">
                                    {{ Str::headline(request()->segment(2)) }} <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-gray-300 rtl:rotate-180"></i>
            </a>
        </li>
        <li class="text-sm text-gray-500 hover:text-primary dark:text-white/70" aria-current="page">Create {{ Str::headline(request()->segment(2)) }}</li>
    </ol>
</div>

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="box">
            <div class="box-header"><h5 class="box-title">{{ Str::headline(request()->segment(2)) }}</h5></div>
            <div class="box-body">
                <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
		            @csrf
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Name</label>
                            <input id="name" type="text" name="name" class="my-auto ti-form-input @error('name') border-red-500 @enderror" placeholder="name"  value="{{ old('name') }}" />
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Permission</label>
                            <select class="ti-form-select choices-multiple-remove-button  @error('permission') border-red-500 @enderror" name="permission[]" multiple>
                                @foreach ($permissions as $permi)
                                    <option value="{{ $permi }}">{{ $permi }}</option>
                                @endforeach

                            </select>
                            @error('permission.*')
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
<link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
@endsection
@section('scripts')
<script src="{{asset('assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script>
    (function () {
    "use strict";
    /* multi select with remove button */
    const multipleCancelButton = new Choices(
        '.choices-multiple-remove-button',
        {
        allowHTML: true,
        removeItemButton: true,
        }
    );
    })();
</script>

@endsection

