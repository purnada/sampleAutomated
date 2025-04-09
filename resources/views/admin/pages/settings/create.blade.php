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
            <a class="flex items-center font-semibold text-primary hover:text-primary dark:text-primary truncate" href="{{ route('admin.settings.index') }}">
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
                <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
		            @csrf
                    <div class="border-b-0 border-gray-200 dark:border-white/10">
                        <nav class="flex space-x-2 rtl:space-x-reverse" aria-label="Tabs">
                            <button
                                type="button"
                                class="hs-tab-active:bg-white hs-tab-active:border-b-transparent hs-tab-active:text-primary dark:hs-tab-active:bg-transparent dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-primary -mb-px py-2 px-3 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-sm hover:text-gray-700 dark:bg-black/20 dark:border-white/10 dark:text-white/70 dark:hover:text-gray-300 active"
                                id="general"
                                data-hs-tab="#hs-tab-js-behavior-1"
                                aria-controls="hs-tab-js-behavior-1"
                            >
                                General
                            </button>
                            <button
                                type="button"
                                class="hs-tab-active:bg-white hs-tab-active:border-b-transparent hs-tab-active:text-primary dark:hs-tab-active:bg-transparent dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-primary -mb-px py-2 px-3 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-sm hover:text-gray-700 dark:bg-black/20 dark:border-white/10 dark:text-white/70 dark:hover:text-gray-300"
                                id="detail"
                                data-hs-tab="#hs-tab-js-behavior-2"
                                aria-controls="hs-tab-js-behavior-2"
                            >
                                Detail
                            </button>
                            <button
                                type="button"
                                class="hs-tab-active:bg-white hs-tab-active:border-b-transparent hs-tab-active:text-primary dark:hs-tab-active:bg-transparent dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-primary -mb-px py-2 px-3 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-sm hover:text-gray-700 dark:bg-black/20 dark:border-white/10 dark:text-white/70 dark:hover:text-gray-300"
                                id="email_setting"
                                data-hs-tab="#hs-tab-js-behavior-3"
                                aria-controls="hs-tab-js-behavior-3"
                            >
                                Email
                            </button>
                            <button
                                type="button"
                                class="hs-tab-active:bg-white hs-tab-active:border-b-transparent hs-tab-active:text-primary dark:hs-tab-active:bg-transparent dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-primary -mb-px py-2 px-3 inline-flex items-center gap-2 bg-gray-50 text-sm font-medium text-center border text-gray-500 rounded-t-sm hover:text-gray-700 dark:bg-black/20 dark:border-white/10 dark:text-white/70 dark:hover:text-gray-300"
                                id="social"
                                data-hs-tab="#hs-tab-js-behavior-4"
                                aria-controls="hs-tab-js-behavior-4"
                            >
                                Social
                            </button>
                        </nav>
                    </div>
                    <div class="">
                        <div id="hs-tab-js-behavior-1" role="tabpanel" aria-labelledby="general">
                            <div class="grid lg:grid-cols-2 gap-6 mt-5">
                            <div class="space-y-2 ">
                                <label class="ti-form-label mb-0 required">Email</label>
                                <input id="email" type="email" name="email" class="my-auto ti-form-input @error('email') border-red-500 @enderror" placeholder="email"  value="{{ old('email') }}" />
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2 ">
                                <label class="ti-form-label mb-0">Latitude</label>
                                <input id="latitude" type="text" name="latitude" class="my-auto ti-form-input @error('latitude') border-red-500 @enderror" placeholder="latitude"  value="{{ old('latitude') }}" />
                                @error('latitude')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2 ">
                                <label class="ti-form-label mb-0">Longitude</label>
                                <input id="longitude" type="text" name="longitude" class="my-auto ti-form-input @error('longitude') border-red-500 @enderror" placeholder="longitude"  value="{{ old('longitude') }}" />
                                @error('longitude')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0 required">Logo</label>
                                <input id="logo" type="file" name="logo" class="my-auto block w-full border border-gray-200 focus:shadow-sm dark:focus:shadow-white/10 rounded-sm text-sm focus:z-10 focus:outline-0 focus:border-gray-200 dark:focus:border-white/10 dark:border-white/10 dark:text-white/70
                                      file:bg-transparent file:border-0
                                      file:bg-gray-100 ltr:file:mr-4 rtl:file:ml-4
                                      file:py-3 file:px-4
                                      dark:file:bg-black/20 dark:file:text-white/70 @error('logo') border-red-500 @enderror" placeholder="logo"  value="{{ old('logo') }}" />
                                @error('logo')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="ti-form-label mb-0 required">Icon</label>
                                <input id="icon" type="file" name="icon" class="my-auto block w-full border border-gray-200 focus:shadow-sm dark:focus:shadow-white/10 rounded-sm text-sm focus:z-10 focus:outline-0 focus:border-gray-200 dark:focus:border-white/10 dark:border-white/10 dark:text-white/70
                                      file:bg-transparent file:border-0
                                      file:bg-gray-100 ltr:file:mr-4 rtl:file:ml-4
                                      file:py-3 file:px-4
                                      dark:file:bg-black/20 dark:file:text-white/70 @error('icon') border-red-500 @enderror" placeholder="icon"  value="{{ old('icon') }}" />
                                @error('icon')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2 ">
                            <label class="ti-form-label ">Google Analytics</label>
                            <textarea id="analytics" name="analytics" class="ti-form-input @error('image') border-red-500 @enderror">{{ old('analytics') }}</textarea>
                            @error('analytics')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            </div>
                            </div>
                        </div>
                        <div id="hs-tab-js-behavior-2" class="hidden" role="tabpanel" aria-labelledby="detail">
                            <div class="border-b border-gray-200 dark:border-white/10">
                                <nav class="-mb-0.5 flex justify-end space-x-6 rtl:space-x-reverse" aria-label="Tabs">
                                    @foreach ($languages as $language)
                                    <button
                                        type="button"
                                        class="hs-tab-active:font-semibold hs-tab-active:border-primary hs-tab-active:text-primary py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-primary dark:text-white/70 {{ $loop->first ? 'active' : '' }}"
                                        id="horizontal-right-alignment-item-{{ $language->id }}"
                                        data-hs-tab="#horizontal-right-alignment-{{ $language->id }}"
                                        aria-controls="horizontal-right-alignment-{{ $language->id }}"
                                    >
                                    {{ $language->title }} @if ($language->flag)
                                        <img src="{{ $language->flag }}">
                                    @endif
                                    </button>
                                    @endforeach

                                </nav>
                            </div>
                            <div class="mt-3">
                                @foreach ($languages as $language)
                                <div id="horizontal-right-alignment-{{ $language->id }}" class="{{ $loop->first ? 'active' : 'hidden' }}" role="tabpanel" aria-labelledby="horizontal-right-alignment-item-{{ $language->id }}">
                                    <div class="grid lg:grid-cols-2 gap-6 mt-5 ml-5">
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0 required">Name</label>
                                            <input id="name" type="text" name="detail[{{ $language->id }}][name]"
                                                class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.name') ? 'border-red-500' : '' }}"
                                                placeholder="name"  value="{{ old('detail.'.$language->id.'.name') }}" />
                                            @if($errors->has('detail.'.$language->id.'.name'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.name') }}</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0 required">Phone Number</label>
                                            <input id="telephone" type="text" name="detail[{{ $language->id }}][telephone]"
                                                class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.telephone') ? 'border-red-500' : '' }}"
                                                placeholder="+9779841000000"  value="{{ old('detail.'.$language->id.'.telephone') }}" />

                                            @if($errors->has('detail.'.$language->id.'.telephone'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.telephone') }}</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0">Address</label>
                                            <input id="address" type="text"
                                                name="detail[{{ $language->id }}][address]"
                                                class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.address') ? 'border-red-500' : '' }}"
                                                placeholder="address"  value="{{ old('detail.'.$language->id.'.address') }}" />
                                            @if($errors->has('detail.'.$language->id.'.address'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.address') }}</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0 required">Meta Title</label>
                                            <input id="meta_title"
                                                name="detail[{{ $language->id }}][meta_title]"
                                                type="text" class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.meta_title') ? 'border-red-500' : '' }}"
                                                placeholder="Meta Title"
                                                value="{{ old('detail.'.$language->id.'.meta_title') }}" />
                                            @if($errors->has('detail.'.$language->id.'.meta_title'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.meta_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0">Meta Keyword</label>
                                            <input id="meta_keyword"
                                                name="detail[{{ $language->id }}][meta_keyword]"
                                                type="text" class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.meta_keyword') ? 'border-red-500' : '' }}"
                                                placeholder="Meta Keyword"
                                                value="{{ old('detail.'.$language->id.'.meta_keyword') }}" />
                                            @if($errors->has('detail.'.$language->id.'.meta_keyword'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.meta_keyword') }}</span>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            <label class="ti-form-label mb-0">Meta Description</label>
                                            <input id="meta_description" name="detail[{{ $language->id }}][meta_description]"
                                                type="text"
                                                class="my-auto ti-form-input {{ $errors->has('detail.'.$language->id.'.meta_description') ? 'border-red-500' : '' }}"
                                                placeholder="Meta Description"
                                                value="{{ old('detail.'.$language->id.'.meta_description') }}" />
                                            @if($errors->has('detail.'.$language->id.'.meta_description'))
                                                <span class="text-red-500 text-xs">{{ $errors->first('detail.'.$language->id.'.meta_description') }}</span>
                                            @endif
                                        </div>


                                    </div>
                                </div>
                                @endforeach

                            </div>

                        </div>
                        <div id="hs-tab-js-behavior-3" class="hidden" role="tabpanel" aria-labelledby="email_setting">
                            <div class="grid lg:grid-cols-2 gap-6 mt-5">
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0 required">Protocol</label>
                                    <select id="protocol" class="my-auto ti-form-input @error('protocol') border-red-500 @enderror" name="protocol">
                                        @foreach ($protocols as $protocol)
                                        <option value="{{ $protocol['value'] }}" @selected($protocol['value'] == old('protocol'))>{{ $protocol['title'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('protocol')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Parameter</label>
                                    <input id="parameter" type="text" name="parameter" class="my-auto ti-form-input @error('parameter') border-red-500 @enderror" placeholder="john@domain.com"  value="{{ old('parameter') }}" />
                                    @error('parameter')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Host Name</label>
                                    <input id="host_name" type="text" name="host_name" class="my-auto ti-form-input @error('host_name') border-red-500 @enderror" placeholder="smpt.google.com"  value="{{ old('host_name') }}" />
                                    @error('host_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">User Name</label>
                                    <input id="username" name="username" type="text" class="my-auto ti-form-input @error('username') border-red-500 @enderror" placeholder="john@domain.com"  value="{{ old('username') }}" />
                                    @error('username')
                                        <span class=" text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Password</label>
                                    <input id="password" name="password" type="text" class="my-auto ti-form-input @error('password') border-red-500 @enderror" placeholder="password"  value="{{ old('password') }}" />
                                    @error('password')
                                        <span class=" text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">SMTP Poart</label>
                                    <input id="smtp_port" name="smtp_port" type="text" class="my-auto ti-form-input @error('smtp_port') border-red-500 @enderror" placeholder="995"  value="{{ old('smtp_port') }}" />
                                    @error('smtp_port')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Encryption</label>
                                    <input id="encryption" name="encryption" type="text" class="my-auto ti-form-input @error('encryption') border-red-500 @enderror" placeholder="tls"  value="{{ old('encryption') }}" />
                                    @error('encryption')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="hs-tab-js-behavior-4" class="hidden" role="tabpanel" aria-labelledby="social">
                            <div class="grid lg:grid-cols-2 gap-6 mt-5">

                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Facebook</label>
                                    <input id="facebook" type="text" name="facebook" class="my-auto ti-form-input @error('facebook') border-red-500 @enderror" placeholder="https://facebook.com/page-url"  value="{{ old('facebook') }}" />
                                    @error('facebook')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Linked In</label>
                                    <input id="linked_in" type="text" name="linked_in" class="my-auto ti-form-input @error('linked_in') border-red-500 @enderror" placeholder="https://linkedin.com/page-url"  value="{{ old('linked_in') }}" />
                                    @error('linked_in')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Twitter</label>
                                    <input id="twitter" name="twitter" type="text" class="my-auto ti-form-input @error('twitter') border-red-500 @enderror" placeholder="https://twitter.com/page-url"  value="{{ old('twitter') }}" />
                                    @error('twitter')
                                        <span class=" text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Youtube</label>
                                    <input id="youtube" name="meta_title" type="text" class="my-auto ti-form-input @error('youtube') border-red-500 @enderror" placeholder="https://youtube.com/page-url"  value="{{ old('youtube') }}" />
                                    @error('youtube')
                                        <span class=" text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">Instagram</label>
                                    <input id="instagram" name="instagram" type="text" class="my-auto ti-form-input @error('instagram') border-red-500 @enderror" placeholder="https://instagram.com/page-url"  value="{{ old('instagram') }}" />
                                    @error('instagram')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0">TikTok</label>
                                    <input id="tiktok" name="tiktok" type="text" class="my-auto ti-form-input @error('tiktok') border-red-500 @enderror" placeholder="https://tiktok.com/page-url"  value="{{ old('tiktok') }}" />
                                    @error('tiktok')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>



                    <button value="submit" type="submit" class="ti-btn ti-btn-primary ti-custom-validate-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

