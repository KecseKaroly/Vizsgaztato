@extends('layouts.app')
@section('title', 'Kurzusok')
@section('content')

    <div class="mt-16">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden lg:w-10/12 md:w-8/12 w-11/12">
            <div class="lg:flex lg:justify-between mb-12 ">
                <div class="font-black text-3xl">Kurzusok</div>
                <div class="w-fit">
                    @can('create', App\Models\Course::class)
                        <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                            <a href="{{ route('courses.create') }}"><i class="fa-solid fa-circle-plus"></i> Kurzus létrehozása</a>
                        </button>
                    @endcan
                </div>
            </div>

            <div class="bg-slate-50 w-full rounded-xl divide-y-4 divide-gray-400 divide-double">
                @cannot('create', App\Models\Course::class)
                    <div class="my-6 w-10/12 mx-auto flex justify-between flex-wrap">

                        <a class="nav-link" href="{{ route('inv_requests') }}">
                            <button type="button" class="relative inline-flex items-center p-2 text-md font-semibold text-center text-white border rounded-lg bg-cyan-600 hover:bg-cyan-900 ">
                                Meghívók
                                <span class="sr-only">Meghívók</span>
                                @if(1 > 0)
                                    <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">1</div>
                                @endif
                            </button>
                        </a>
                    </div>
                @endcannot
                <div class="w-full">
                    @forelse($courses as $course)
                        <div class="bg-slate-600 w-10/12 mx-auto mt-4 mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center">
                            <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2">
                                <a href="{{ route('courses.show', $course->id) }}">
                                    {{ $course->title }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('courses.show', $course->id) }}">
                                    <button class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-lg font-semibold italic py-4">
                            @if(auth()->user()->is_student)
                                Üresség... Még nincsen kurzusom
                            @else
                                Még nem hoztam létre kurzust.
                            @endif
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection
