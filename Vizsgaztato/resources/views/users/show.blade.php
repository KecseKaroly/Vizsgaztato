@extends('layouts.app')
@section('title',  'Profilom' )
@section('content')
    <div class="flex flex-col items-center justify-left border rounded-3xl bg-slate-50 mt-16 md:mx-24 mx-8 py-8">
        <div class="text-4xl font-bold mb-8 text-center">Profilom</div>
        <hr class="w-10/12 h-1 mx-auto bg-gray-100 border-0 rounded md:mb-10 dark:bg-gray-700">
        <div class="w-full flex flex-col items-center ">
            <form class="w-9/12" method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="name">
                            Teljes név
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('name') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="name" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="name" value="{{ $user->name }}" autofocus>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="username">
                            Felhasználónév
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('username') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ $user->username }}" autocomplete="username">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-2">
                    <div class="md:w-4/12">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8" for="email">
                            E-mail cím
                        </label>
                    </div>
                    <div class="md:w-4/12">
                        @error('email') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                        <input
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                            id="email" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="email" value="{{ $user->email }}" autofocus autocomplete="email">
                    </div>
                </div>
                <div class="md:flex md:items-center">
                    <div class="md:w-4/12"></div>
                    <div class="md:w-4/12">
                        <button
                            class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ __('Módosítás') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
