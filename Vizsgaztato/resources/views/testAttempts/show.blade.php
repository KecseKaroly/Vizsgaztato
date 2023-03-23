@extends('layouts.app')
@section('title', 'Kitöltött feladatlapok')
@section('content')
    <div class="mt-4 mb-24 select-none">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{route('test.index', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="text-center mb-12 font-black text-3xl italic">Kitöltött feladatlapok</div>
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 text-gray-100">
                @forelse($course->users as $user)
                        <div class="bg-slate-600 rounded-lg flex w-full justify-between text-2xl py-2 px-2">
                                <div class="flex divide-x-2 content-center">
                                    <div class="px-4 my-auto"><i class="fa-solid fa-users"></i></div>
                                    <div class="px-4 font-bold">{{ $user->name }}</div>
                                </div>
                                <div class="pr-6 my-auto">
                                    <button data-user_id="{{ $user->id }}"
                                            class="user_id hover:cursor-pointer hover:bg-slate-800 text-2xl w-10 h-10 rounded-full bg-slate-400  text-white">
                                        <i class="fa-solid fa-angles-down" id="arrow_of_user{{ $user->id }}"></i>
                                    </button>
                                </div>
                        </div>
                        <div id="attempts_of_user{{ $user->id }}"
                             style="display: none;" class="divide-y-2">
                            @forelse($user->attempts as $testAttempt )
                                <div id="testAttempt#{{ $testAttempt->id }}"
                                     class="hover:bg-blue-400 bg-slate-400 md:flex md:justify-between items-center md:w-10/12 w-11/12 px-12 py-2 md:ml-32 ml-8">
                                    @if($testAttempt->submitted)
                                    <a href="{{ route('testAttempts.show', [$course, $testAttempt->id]) }}"
                                           target="_blank">
                                            <button
                                                class="bg-lime-500 hover:bg-lime-300 p-1.5 border rounded-lg text-lime-900 ">
                                                <i class="fa-solid fa-eye"></i> Megtekint
                                            </button>
                                        </a>
                                    @else
                                        <button
                                            class="bg-stone-300 p-1.5 border rounded-lg text-lime-900 cursor-default">
                                            Kitöltés alatt...
                                        </button>
                                    @endif
                                    <div>
                                        {{ $testAttempt->achievedScore }}
                                        /{{ $testAttempt->maxScore }}
                                    </div>
                                    <div>
                                        Kitöltve: {{ $testAttempt->created_at }}
                                    </div>
                                    <div>
                                        <form>
                                            <button data-id="{{ $testAttempt->id }}"
                                                    class="deleteTestAttempt rounded-full md:w-10 md:h-10 w-6 h-6 bg-red-600 hover:bg-red-800">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="hover:bg-blue-400 bg-slate-400 rounded-md flex items-center w-10/12 justify-between text-xl px-12 py-3 ml-32">
                                    <div>
                                        Még nincsen kitöltött tesztje...
                                    </div>
                                </div>
                            @endforelse
                        </div>

                @empty
                    Nincsen még cspoport
                @endforelse
            </div>
        </div>
    </div>


@endsection
