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
        .navbar .dropdown:hover .dropdown-menu, .navbar .dropdown .dropdown-menu:hover {
            display:block!important;
        }

        .navbar .dropdown-mega { position:static; }

        .navbar .mega {
            width:100%;
            left:0;
            right:0;
           /*  height of nav-item  */
            top:45px;
            background: #fafafa;
            border: 1px solid #000;
            border-right: none;
            border-left: none;
            border-top: none;
            border-radius: 0;
        }
/* .navbar-light .navbar-nav .nav-link {
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
        } */

        .secondrow {
            margin-top: 1em;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel">
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
                        @foreach($types as $type)
                           <li class="nav-item dropdown dropdown-mea">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $type->type }}
                                </a>
                                <div class="dropdown-menu mea" aria-labelledby="navbarDropdown">
                                    <ul class="nav flex-column">
                                        @foreach($type->devices as $device)
                                            <li><a class="dropdown-item" href="/lookup/device/{{ $device->id }}"> {{ $device->brand->name }} {{ $device->model_name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @include('helpers.selectLocation')
                        <!-- Authentication Links -->
                        @include('barcodesearch')
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @include('global.notifications')
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
