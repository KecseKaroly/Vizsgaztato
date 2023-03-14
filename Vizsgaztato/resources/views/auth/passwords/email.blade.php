@extends('layouts.app')
@section('title', 'Elfelejtett jelszó')

@section('content')
    <div class="flex flex-col items-center justify-left border rounded-3xl bg-slate-50 mt-16 md:mx-24 mx-8 py-8">
        <div class="text-4xl font-bold mb-8 text-center">Elfelejtett jelszó</div>
        <hr class="w-10/12 h-1 mx-auto bg-gray-100 border-0 rounded md:mb-10 dark:bg-gray-700">
        <div class="w-full flex flex-col items-center">
            @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
            @endif
            @error('email')
            <div class="mb-3 text-red-600">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            <form class="w-9/12" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label for="email"
                               class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8">{{ __('Email cím:') }}</label>

                    </div>
                    <div class="md:w-4/12">
                        <input id="email" type="email"
                               class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500 @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>

                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-4/12"></div>
                    <div class="md:w-4/12">
                        <button
                            class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ __('Link küldése') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')


