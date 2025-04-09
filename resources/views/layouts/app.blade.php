<html lang="en" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="dark">
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
        @yield('styles')
        <meta http-equiv="imagetoolbar" content="no" />

    </head>
    <body class="cover1 justify-center">
        @yield('content')
        <script src="{{ asset('assets/libs/popperjs/core/umd/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom-switcher.js') }}"></script>
        <script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
    </body>
</html>
