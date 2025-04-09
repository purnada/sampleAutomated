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
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.districts.index') }}">
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
                <form method="POST" action="{{ route('admin.districts.store') }}" enctype="multipart/form-data">
		            @csrf
                    <div class="grid lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Province</label>
                            <select class="my-auto ti-form-input @error('province_id') border-red-500 @enderror" name="province_id">
                                @foreach ($provinces as $title => $id)
                                    <option value="{{ $id }}" @selected($id == old('province_id'))>{{ $title }}</option>
                                @endforeach
                            </select>

                            @error('province_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Title</label>
                            <input id="title" type="text" name="title" class="my-auto ti-form-input @error('title') border-red-500 @enderror" placeholder="Title"  value="{{ old('title') }}" />
                            @error('title')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="ti-form-label mb-0">Nepali Title</label>
                            <input id="name_nep" type="text" name="name_nep" class="my-auto ti-form-input @error('name_nep') border-red-500 @enderror" placeholder="काठमाण्डोेेें"  value="{{ old('name_nep') }}" />
                            @error('name_nep')
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

