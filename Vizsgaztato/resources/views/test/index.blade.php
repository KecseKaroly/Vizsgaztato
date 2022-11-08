@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsorok</p> 
        <div class="md:flex ">
            <div><a href="{{ route('test.create') }}">Új vizsgasor</a></div>
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">

                @foreach ($tests as $test )
                <a href="{{route('checkTestResults', [$test->id])}}">
                    <div class="flow-root lg:px-12 md:px-9sm:px-6 px-3 py-3 my-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg mt-9">
                        <div class="float-left text-2xl font-black font-mono">{{$test->title}}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
