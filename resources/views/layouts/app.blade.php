<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .navbar-light .navbar-nav .nav-link {
            color: rgb(64, 64, 64);
        }
        .btco-menu li > a {
            padding: 10px 15px;
            color: #000;

        }

        .btco-menu .active a:focus,
        .btco-menu li a:focus ,
        .navbar > .show > a:focus{
            background: transparent;
            outline: 0;
        }

        .dropdown-menu .show > .dropdown-toggle::after{
            transform: rotate(-90deg);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-target="#" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    iPhone
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="/device/26a2aa03-9aba-4f8a-a6c4-760cb9591cca">6</a></li>
                                    <li><a class="dropdown-item" href="/device/b229c140-4505-4734-b894-76b2b39413b8">6 Plus</a></li>
                                    <li><a class="dropdown-item" href="/device/6fbd9d65-9498-4e0e-a50c-c35992421be5">6S</a></li>
                                    <li><a class="dropdown-item" href="/device/64f4830c-6eba-4428-a564-1d0473ed8b44">6S Plus</a></li>
                                </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @include('barcodesearch')
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>