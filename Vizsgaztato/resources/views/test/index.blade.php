@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-10/12">Vizsga feladatsorok</div>
            <div class="w-2/12 pb-5">
                <div class="w-48 ml-auto">
                    <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                        <a href="{{ route('test.create') }}">Új vizsgasor</a>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:flex">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">
                @foreach ($tests as $test )
                <a href="{{route('checkTestResults', [$test->id])}}">
                    <div class="flex flex-col lg:px-12 md:px-9sm:px-6 px-3 py-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg my-9">
                        <div class="md:flex">
                            <div class="text-2xl font-black font-mono truncate w-10/12">{{$test->title}}</div>
                            <div class="block"><a href="{{ route('test.edit', $test->id) }}">Szerkesztés</a></div>
                        </div>
                        <div>
                            <div class="md:flex justify-between">
                                <div class="flex"><div class="text-md font-medium font-mono black italic">Tanár:</div><div>Minta Márton</div></div>
                                <div class="flex"><div class="text-md font-medium font-mono black italic">Tárgy:</div><div>PéldaÓra</div></div>
                                <div class="flex"><div class="text-md font-medium font-mono black italic">Osztály:</div><div>PéldaOsztály</div></div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
