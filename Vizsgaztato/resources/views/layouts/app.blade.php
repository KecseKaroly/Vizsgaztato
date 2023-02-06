<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/flowbite.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9b89b1cf87.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://kit.fontawesome.com/9b89b1cf87.css" crossorigin="anonymous">
    @vite('resources/css/app.css')
    @vite('resources/js/ajax.js')
    @yield('style')
    @livewireStyles
</head>
<body class="bg-slate-300">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-5">
            <div class="container">
                <div class="flex justify-between">
                    <div class="flex text-2xl font-bold italic">Online vizsgáztató rendszer</div>
                    @guest
                        <div class="flex">
                            <div class="flex">
                                @if (Route::has('login'))
                                    <div class="flex-1 hover:bg-slate-400 px-2 py-1 border  rounded ">
                                        <li class="nav-item list-none">
                                            <a class="nav-link" href="{{ route('login') }}">{{ __('Bejelentkezés') }}</a>
                                        </li>
                                    </div>
                                @endif
                                @if (Route::has('register'))
                                    <div class="flex-1 ml-8 hover:bg-slate-400 px-2 py-1 border  rounded ">
                                        <li class="nav-item list-none">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Regisztráció') }}</a>
                                        </li>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @else
                        <div class=" hover:bg-slate-400 px-2 py-1 border  rounded ">
                            <li class="nav-item list-none">
                                <a class="nav-link" href="{{ route('test.index') }}">{{ __('Tesztek') }}</a>
                            </li>
                        </div>
                        <div class=" hover:bg-slate-400 px-2 py-1 border  rounded ">
                            <li class="nav-item list-none">
                                <a class="nav-link" href="{{ route('groups.index') }}">{{ __('Csoportok') }}</a>
                            </li>
                        </div>
                        <div class=" hover:bg-slate-400 px-2 py-1 border  rounded ">
                            <li class="nav-item list-none">
                                <a class="nav-link" href="{{ route('inv_requests') }}">{{ __('Meghívók') }}</a>
                            </li>
                        </div>
                        <button  data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">{{ Auth::user()->name  }}</button>
                        <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                              <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profil</a>
                              </li>
                              <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Eredményeim</a>
                              </li>
                              <li class="">
                                <a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white bold text-red-600" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Kijelentkezés') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/flowbite.min.js"></script>

    @livewireScripts
</body>
</html>
