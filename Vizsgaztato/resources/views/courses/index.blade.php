@extends('layouts.app')
@section('title', 'Kurzusok')
@section('content')
    <div class="mt-4">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-12 ">
                <div class="font-black text-3xl">Kurzusok</div>
                <div class="w-fit mt-3">
                    @can('create', App\Models\Course::class)
                        <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                            <a href="{{ route('courses.create') }}"><i class="fa-solid fa-circle-plus"></i> Kurzus létrehozása</a>
                        </button>
                    @endcan
                </div>
            </div>

            <div class="bg-yellow-200 rounded-xl divide-y-4 divide-gray-400 divide-double">
                    @forelse($courses as $course)
                        <div class="bg-yellow-600 w-10/12 mx-auto mt-4 mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center">
                            <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2">
                                <a href="{{ route('courses.show', $course->id) }}">
                                    {{ $course->title }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('courses.show', $course->id) }}">
                                    <button class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-yellow-400 hover:bg-yellow-500 text-white">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-lg font-semibold italic py-4">
                            @if(auth()->user()->auth)
                                Üresség... Még nincsen kurzusom
                            @else
                                Még nem hoztam létre kurzust.
                            @endif
                        </div>
                    @endforelse
                {{ $courses->links() }}
            </div>
        </div>
    </div>
@endsection
