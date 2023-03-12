@extends('layouts.app')
@section('title', 'Vizsga feladatsor eredményei')
@section('content')
<div class="flex">
    <div class="md:w-1/12 md:mr-0 ml-8 mr-24">
        <button class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
            <a href="{{ route('test.index', $course) }}">Vissza</a>
        </button>
    </div>
    <div class="mt-5 mb-24 select-none w-4/5">
        <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
            <div class="text-center font-black text-3xl">[{{ $test->title }}]Vizsga feladatsor eredményei</div>
            <div class="text-yellow-600 text-center font-black m-6  mb-12  font-bold text-xl">Hártalévő próbálkozások száma: {{ ($test->maxAttempts - count($submitted) - count($ongoing)) }}</div>
            <div class="md:flex ">
                <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">
                    <div class="text-center mt-12 mb-6">
                        <a href="{{ route('test.show', [$test]) }}">
                            <button @class(["w-6/12 focus:outline-none text-white bg-sky-400 hover:bg-sky-600 font-bold rounded-xl text-md py-3.5",
                                                "opacity-50 cursor-not-allowed" => !count($ongoing) && count($submitted) >= $test->maxAttempts])
                                @disabled(!count($ongoing) && count($submitted) >= $test->maxAttempts)>
                                Feladatsor kitöltése
                            </button>
                        </a>
                    </div>

                    @foreach ($ongoing as $testAttemptIndex => $testAttempt )
                        <a href="{{route('testAttempts.show', [$course, $testAttempt->id])}}">
                            <div class="flow-root lg:px-12 md:px-9sm:px-6 px-3 py-3 my-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg mt-9 ">
                                <div class="float-left text-2xl font-black font-mono">{{$test->title}}</div>
                                @if($testAttempt->submitted)
                                    <div class="float-right">{{$testAttempt->achievedScore}} / {{$testAttempt->maxScore}}</div>
                                @else
                                    <div class="float-right text-red-800 font-black italic">Kitöltés alatt...</div>
                                @endif
                            </div>
                        </a>
                    @endforeach

                    @forelse ($submitted as $testAttemptIndex => $testAttempt )
                        <a href="{{route('testAttempts.show', [$course, $testAttempt->id])}}">
                            <div class="flow-root lg:px-12 md:px-9sm:px-6 px-3 py-3 my-3 max-w w-full bg-gray-400 rounded-lg  shadow-lg mt-9 ">
                                <div class="float-left text-2xl font-black font-mono">{{ $testAttempt->created_at }}</div>
                                @if($testAttempt->submitted)
                                    <div class="float-right">{{$testAttempt->achievedScore}} / {{$testAttempt->maxScore}}</div>
                                @else
                                    <div class="float-right text-red-800 font-black italic">Kitöltés alatt...</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-red-500 text-center m-6">Még nincsen kitöltött tesztje!</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
