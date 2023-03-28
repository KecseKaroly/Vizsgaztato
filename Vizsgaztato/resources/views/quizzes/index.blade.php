@extends('layouts.app')
@section('title', 'Kvízek')
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{route('courses.show', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-teal-200 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="w-fit">
                @can('create', [App\Models\Module::class, $course])
                    <button type="button"
                            class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                        <a href="{{ route('quizzes.create', [$course]) }}"><i class="fa-solid fa-circle-plus"></i> Kvíz hozzáadása</a>
                    </button>
                @endcan
            </div>
            <div
                class="bg-slate-50 w-full rounded-xl divide-gray-400 divide-double mt-4">
                        @forelse($quizzes as $quiz)
                            <div class="bg-slate-600 w-11/12 mx-auto mt-2 mb-2 text-gray-100 text-center md:px-6 px-3 md:py-2 py-1 flex">
                                <div class="md:flex md:flex-wrap md:justify-between w-full">
                                    <div class="text-lg font-bold text-left basis-1/3">
                                        {{$loop->iteration}}. kvíz
                                        {{ \Illuminate\Support\Str::limit($quiz->title, 6, $end='...')}}
                                    </div>
                                    <div class="basis-1/3">
                                        Modul: {{ $quiz->module->title }}
                                    </div>
                                    <div class="flex sm:justify-evenly basis-1/3">
                                        @can('delete', $quiz)
                                            <form method="POST" action="{{route('quizzes.destroy', $quiz) }}">
                                                @method('DELETE')
                                                @csrf
                                                <button
                                                    class="deleteQuizBtn bg-red-50 hover:bg-red-500 text-red-500 hover:text-red-50 border-4 border-red-500 hover:border-red-50 rounded-lg  font-semibold md:text-2xl text-sm w-fit py-1.5 px-2">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('update', $quiz)
                                            <a href="{{ route('quizzes.edit', [$course, $quiz]) }}">
                                                <button
                                                    class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg font-semibold md:text-2xl text-sm w-fit py-1.5 px-2">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                                <div class="">
                                    <a href="{{ route('quizzes.show', $quiz) }}">
                                        <button
                                            class="text-2xl md:w-12 md:h-12 h-full w-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div colspan="2" class="text-center text-lg font-semibold italic py-4">
                                A kurzushoz még nincsen modul elkészítve... talán később
                            </div>
                        @endforelse
                {{ $quizzes->links() }}
            </div>
        </div>
    </div>
@endsection
