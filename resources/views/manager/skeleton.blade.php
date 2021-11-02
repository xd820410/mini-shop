<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
        <title>@yield('title')</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <!-- <script src="{{ asset('js/bootstrap/popper.min.js') }}"></script> -->
        <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
        <!-- <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script> -->
        <script src="{{ asset('js/helper.js') }}"></script>

        <!-- Fonts -->
        <!-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
        <style>
        </style>
    </head>
    <body>
        <div id="app">
            @include('navigation_bar')

            <main class="py-4">
                @yield('content')
            </main>
        </div>

        <script>
            var baseUrl = "{{ url('/') }}"
        </script>
        @yield('custom_js')
    </body>
</html>
