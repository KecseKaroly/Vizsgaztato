@extends('layouts.app')
@section('title', 'Bejelentkezés')

@section('content')
    <div class="flex flex-col items-center justify-left border rounded-3xl bg-slate-50 mt-16 md:mx-24 mx-8 py-8">
        <div class="text-4xl font-bold mb-8 text-center">Bejelentkezés</div>
        <hr class="w-1/2 h-1 mx-auto bg-gray-100 border-0 rounded md:mb-10 dark:bg-gray-700">
        <div class="w-full flex flex-col items-center ">

            @error('password')
            <div class="mb-3 text-red-600">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            @error('username')
            <div class="mb-3 text-red-600">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            <form class="w-9/12" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="username">
                            Felhasználónév
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="password">
                            Jelszó
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                    </div>
                </div>
                @if (Route::has('password.request'))
                    <div class="md:flex md:items-center md:text-right mb-2">
                        <div class="md:w-4/12">
                        </div>
                        <div class="md:w-4/12">
                            <a class="btn btn-link block text-gray-500 font-bold mb-1 md:mb-0"
                               href="{{ route('password.request') }}">
                                {{ __('Elfelejtett jelszó') }}
                            </a>
                        </div>
                    </div>
                @endif

                <div class="md:flex md:items-center md:text-left my-6">
                    <div class="md:w-4/12">
                    </div>
                    <div class="md:w-4/12">
                        Nincs még fiókja?
                        <a class="text-blue-500 font-bold mb-1 md:mb-0"
                           href="{{ route('register') }}">
                            {{ __('Itt regisztrálhat!') }}
                        </a>
                    </div>
                </div>

                <div class="md:flex md:items-center mb-4">
                    <div class="md:w-1/3"></div>
                    <div class="md:w-2/3">
                        <input class="form-check-input" type="checkbox" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Emlékezz rám') }}
                        </label>
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3"></div>
                    <div class="md:w-2/3">
                        <button
                            class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ __('Bejelentkezés') }}
                        </button>


                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
