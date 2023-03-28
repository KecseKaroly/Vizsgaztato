@extends('layouts.app')
@section('title', 'Csoport létrehozása')
@section('content')
    <div class="mt-4 mb-24 select-none">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{route('groups.index')}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-teal-200 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="text-center mb-12 font-black text-3xl">Csoport létrehozása</div>
            <div class="bg-slate-50 rounded-xl">
                @if($errors->any())
                    <div class="text-red-600 text-center divide-y-2">
                        <h1 class="text-xl font-black">Hiba</h1>
                        <ol>
                            @foreach($errors->all() as $error)
                                <li class="ml-3 italic">{{$error}}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf
                    <div class="md:flex md:items-center mb-6 mt-10">
                        <div class="md:w-2/5">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3"
                                   for="name">
                                Csoport neve
                            </label>
                        </div>
                        <div class="md:w-3/5">
                            <input
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                id="name" type="text" name="name" required autofocus>
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-2/5">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3"
                                   for="invCode">
                                Meghívó kód
                            </label>
                        </div>
                        <div class="md:w-3/5">
                            <input
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                id="invCode" type="text" name="invCode" value="{{ $invCode }}" required
                                readonly>
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-10 md:mx-0 mx-3">
                        <div class="md:w-2/5"></div>
                        <div class="md:w-3/5">
                            <button
                                class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                type="submit">
                                {{ __('Létrehozás') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
