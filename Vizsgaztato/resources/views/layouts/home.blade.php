@extends('layouts.app')
@section('title', 'Főoldal')
@section('content')
    <div class="mt-4">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="text-center mb-12 ">
                <div class="font-black text-3xl">Főoldal</div>
            </div>
            <div class="flex md:flex-row-reverse flex-col">
                <div class="md:pl-5 md:w-1/2 md:h-[75vh] h-[30vh] md:flex md:flex-col md:justify-between">
                    <div
                        class="bg-blue-100 rounded-xl h-[17vh]  md:flex md:flex-col md:space-y-2 md:justify-evenly py-2 overflow-y-auto md:mb-0 mb-4">
                        <div class="flex flex-wrap content-center justify-between px-6 text-center">
                            <div class="mt-2.5 italic font-semibold text-lg w-full">Bejelentkezve mint: {{ auth()->user()->name }}</div>
                            <a href="{{ route('users.show', auth()->user()) }}" class="mx-auto block font-bold text-lg border rounded-lg w-fit bg-blue-400 hover:bg-blue-500 px-12 py-4 ">Profilom</a>
                        </div>
                    </div>
                    <div class="bg-red-100 rounded-xl">
                        <div class="flex content-end justify-between px-2 pt-2">
                            <div class="font-bold md:text-xl text-base">Csoportok:</div>
                            <a href="{{ route('groups.index') }}" class="italic block text-gray-500 md:text-base text-xs pt-1">Minden
                                csoport megtekintése</a>
                        </div>
                        <div class="md:h-[50vh] h-[30vh] overflow-y-auto md:flex md:flex-col md:space-y-2 md:justify-evenly py-2">
                            @forelse($groups as $group)
                                <div
                                    class="my-2 bg-red-900 w-11/12 mx-auto text-gray-100 px-6 py-2.5 rounded flex flex-wrap justify-between items-center">
                                    <div class="hover:underline text-lg font-semibold">
                                        <a href="{{ route('groups.show', $group->id) }}">
                                            {{ $group->name }}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('groups.show', $group->id) }}">
                                            <button
                                                class="text-2xl md:w-10 md:h-10 w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white">
                                                <i class="fa-solid fa-angles-right"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-lg font-semibold italic py-4">
                                    Még nincsen csoportom
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="md:mt-0 mt-44 bg-yellow-100 rounded-xl md:w-1/2">
                    <div class="flex content-end justify-between px-2 pt-2">
                        <div class="font-bold md:text-xl text-base">Kurzusok:</div>
                        <a href="{{ route('courses.index') }}" class="italic block text-gray-500 md:text-base text-xs pt-1">Minden
                            kurzus megtekintése</a>
                    </div>
                    <div class=" md:h-[70vh] h-[30vh] overflow-y-auto md:flex md:flex-col md:space-y-2 md:justify-evenly py-2">
                        @forelse($courses as $course)
                            <div
                                class="my-2 bg-yellow-700 w-11/12 mx-auto text-gray-100 px-6 py-2.5 rounded flex flex-wrap justify-between items-center">
                                <div class="hover:underline text-lg font-semibold">
                                    <a href="{{ route('courses.show', $course->id) }}">
                                        {{ $course->title }}
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('courses.show', $course->id) }}">
                                        <button
                                            class="text-2xl md:w-10 md:h-10 w-8 h-8 rounded-full bg-yellow-400 hover:bg-yellow-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-lg font-semibold italic py-4">
                                @if(auth()->user()->auth)
                                    Még nincsen kurzusom
                                @else
                                    Még nem hoztam létre kurzust.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
