@extends('layouts.app')
@section('title', 'Vizsga feladatsorok')
@section('content')
    <div class="mt-4 pb-24">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{route('courses.show', $course)}}>">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-12">
                <div class="font-black text-3xl">Vizsga feladatsorok</div>
                <div class="w-fit mt-3">
                    @can('create', App\Models\test::class)
                        <button type="button"
                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                            <a href="{{ route('test.create', $course) }}">Új vizsgasor</a>
                        </button>
                    @endcan
                </div>
            </div>
            <div class="md:flex">
                <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border shadow-xl pb-8">
                    @can('create', App\Models\test::class)
                        @forelse($tests as $test)
                            <div
                                class="bg-slate-600 mx-auto mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center rounded mt-5">
                                <div class="hover:underline text-lg font-bold my-2">
                                    <a href="{{ route('checkTestInfo', [$course, $test]) }}">
                                        {{$test->title}}
                                    </a>
                                </div>
                                <div class="flex">
                                    @can('delete', $test)
                                        <form method="POST" action="{{route('test.destroy', $test) }}">
                                            @method('DELETE')
                                            @csrf
                                            <button
                                                class="deleteQuizBtn bg-red-50 hover:bg-red-500 text-red-500 hover:text-red-50 border-4 border-red-500 hover:border-red-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-solid fa-trash fa-lg"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    @can('update', $test)
                                        <a href="{{ route('test.edit', [$course, $test]) }}">
                                            <button
                                                class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </button>
                                        </a>
                                    @endcan
                                </div>
                                <a href="{{ route('checkTestInfo', [$course, $test]) }}">
                                    <button
                                        class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </button>
                                </a>
                            </div>
                        @empty
                            <div class="text-center text-lg font-semibold italic pt-5">
                                Még nem hoztam létre tesztet...
                            </div>
                        @endforelse
                    @else
                        @forelse($tests as $test)
                            <div
                                class="bg-slate-600 w-10/12 mx-auto mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center rounded mt-5">
                                <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2 w-fit">
                                    <a href="{{ route('testAttempts.index', [$course, $test]) }}">
                                        {{$test->title}}
                                    </a>
                                </div>
                                @if(isset($test->pivot->enabled_from) && isset($test->pivot->enabled_until))
                                    <div>
                                        {{Carbon\Carbon::parse($test->pivot->enabled_from)->format('Y.m.d H:i:s')}}
                                        - {{Carbon\Carbon::parse($test->pivot->enabled_until)->format('Y.m.d H:i:s')}}
                                    </div>
                                    @if($test->pivot->enabled_from < now() && $test->pivot->enabled_until > now())

                                        <a href="{{ route('testAttempts.index', [$course, $test]) }}">
                                            <button class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                                <i class="fa-solid fa-angles-right"></i>                                                </button>
                                        </a>
                                    @else
                                        <button
                                            class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-red-400 hover:bg-red-600 text-white"
                                            disabled>
                                            <i class="fa-solid fa-eye-slash"></i>
                                        </button>
                                    @endif
                                @else
                                    <div>
                                        ?? - ??
                                    </div>

                                    <a href="{{ route('testAttempts.index', [$course, $test]) }}">
                                        <button
                                            class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-lg font-semibold italic pt-5">
                            Nincsen még elkészült teszt
                            </div>
                        @endforelse
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
