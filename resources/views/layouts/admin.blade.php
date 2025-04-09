<html lang="en" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="dark" >
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title','Admin | Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="https://spruko.com/demo/synto/Synto/dist/assets/img/brand-logos/favicon.ico" />
        <script src="{{ asset('assets/js/main.js')}}"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" />
        <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css')}}" />
        <link rel="stylesheet" href="{{ asset('assets/libs/simonwep/pickr/themes/nano.min.css')}}" />
        <link rel="stylesheet" href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css')}}" />
        <link rel="stylesheet" href="{{ asset('assets/libs/toastr/toastr.min.css') }}">
        @yield('styles')
        <meta http-equiv="imagetoolbar" content="no" />

    </head>
    <body class="">
        @include('admin.includes.setting')
        <div class="page">
            @include('admin.includes.sidebar')
            @include('admin.includes.header')
            <div class="content">
                <div class="main-content">
                    @yield('content')
                </div>
            </div>

            <footer class="mt-auto py-3 border-t dark:border-white/10 bg-white dark:bg-bgdark">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center">
                        Copyright © <span id="year"></span> <a href="javascript:void(0)" class="text-primary">purna</a>. Designed with <span class="ri ri-heart-fill text-red-500"></span> by
                        <a class="text-primary" href="javascript:void(0)"> Purna </a> All rights reserved
                    </p>
                </div>
            </footer>
        </div>

        <div class="scrollToTop">
            <span class="arrow"><i class="ri-arrow-up-s-fill text-xl"></i></span>
        </div>
        <div id="responsive-overlay"></div>
        {{-- <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{ asset('assets/libs/chart.js/chart.min.js')}}"></script>
        <script src="{{ asset('assets/js/index.js')}}"></script> --}}


        <script src="{{ asset('assets/libs/popperjs/core/umd/popper.min.js')}}"></script>
        <script src="{{ asset('assets/libs/simonwep/pickr/pickr.es5.min.js')}}"></script>
        <script src="{{ asset('assets/js/defaultmenu.js')}}"></script>
        <script src="{{ asset('assets/js/sticky.js')}}"></script>
        <script src="{{ asset('assets/js/switch.js')}}"></script>
        <script src="{{ asset('assets/libs/preline/preline.js')}}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ asset('assets/js/custom.js')}}"></script>
        <script src="{{ asset('assets/js/custom-switcher.js')}}"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>
        <script type="text/javascript">

            // toast for laravel validation error
            @if ($errors->any())
                @foreach($errors->all() as $er)
                    toastr.error('{{ $er }}')
                @endforeach

            @endif
            @if(Session::has('msg'))
            var type = "{{ Session::get('type') }}";

            switch(type){
                case 'info':
                    break;

                case 'warning':
                    toastr.warning("{{ Session::get('msg') }}");
                    break;

                case 'success':
                    toastr.success("{{ Session::get('msg') }}");
                    break;

                case 'error':
                    toastr.error("{{ Session::get('msg') }}");
                    break;
            }
            @endif
        </script>
        @yield('scripts')
    </body>
</html>
