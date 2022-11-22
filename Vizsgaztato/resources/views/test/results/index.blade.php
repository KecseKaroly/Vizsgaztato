@extends('layouts.app')
@section('title', 'Vizsga feladatsor eredményei')
@section('content')

<div class="mt-24 mb-24 select-none">
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">[{{ $test->title }}]Vizsga feladatsor eredményei</p> 
        <div class="md:flex ">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">
                @if(isset($noAttempts))
                    <div class="text-red-500 text-center m-6">{{ $noAttempts }}</div>
                @else
                    @foreach ($testAttempts as $testAttemptIndex => $testAttempt )
                    <a href="{{route('checkAttemptResult', [$test->id, $testAttempt->id])}}">
                        <div class="flow-root lg:px-12 md:px-9sm:px-6 px-3 py-3 my-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg mt-9 ">
                            <div class="float-left text-2xl font-black font-mono">{{$test->title}}</div>
                            <div class="float-right">{{$testAttempt->achievedScore}} / {{$testAttempt->maxScore}}</div>
                        </div>
                    </a>
                    @endforeach
                @endif
                @if(isset($noAttempts) || count($testAttempts) < $test->maxAttempts)
                    <div class="text-center mt-12 mb-6">
                        <a href="{{ route('test.show', $test->id) }}"><button class="w-6/12 focus:outline-none text-white bg-sky-400 hover:bg-sky-600 font-bold rounded-xl text-md py-3.5">Feladatsor kitöltése</button></a>
                    </div>
                @else
                    <div class="text-red-500 text-center m-6">
                        Elhasználta az összes próbálkozási lehetőséget!
                    </div>
                @endif
            </div>
        </div>
        <div class="flex flex-col items-center">
            <button class="text-center my-8 mx-auto py-2.5 text-lg font-bold text-blue-900 bg-slate-100 w-3/12 rounded-md">
                <a href="{{ route('test.index') }}">Vissza az eredményekhez</a>
            </button>
        </div>
    </div>
</div>
@endsection