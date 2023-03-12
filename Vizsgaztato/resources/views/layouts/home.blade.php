<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Főoldal</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/flowbite.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://kit.fontawesome.com/9b89b1cf87.css" crossorigin="anonymous">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-300">
<div id="app" class="relative  md:h-[89.9vh] h-screen">
    <nav class="bg-white shadow-sm p-5">
            <div class="flex justify-between w-full">
                <a href="{{route('home')}}" class="flex text-2xl font-bold italic">Online vizsgáztató rendszer</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    <button class="font-semibolt text-red-600">{{ __('Kijelentkezés') }}</button>
                </form>
            </div>
    </nav>
    <main class="h-full  text-center text-8xl">

    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/flowbite.min.js"></script>
</body>
</html>
