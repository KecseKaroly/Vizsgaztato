@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsorok</p> 
        <div class="w-full pb-5">
            <div class="w-48 ml-auto">
                <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                    <a href="{{ route('test.create') }}">Új vizsgasor</a>
                </button>
            </div>
        </div>
        <div class="md:flex">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">
                @foreach ($tests as $test )
                <a href="{{route('checkTestResults', [$test->id])}}">
                    <div class="flex flex-col lg:px-12 md:px-9sm:px-6 px-3 py-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg my-9">
                        <div>
                            <p class="text-2xl font-black font-mono truncate ">{{$test->title}}</p>
                        </div>
                        <div>
                            <div class="flex justify-between">
                                <p class="text-md font-medium font-mono">Tanár: Minta Márton</p>
                                <p class="text-md font-medium font-mono">Tárgy: PéldaÓra</p>
                                <p class="text-md font-medium font-mono">Osztály: 1.példa</p>
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
