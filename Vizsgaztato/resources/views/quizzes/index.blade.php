@extends('layouts.app')
@section('title', 'Kvízek')
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 ml-12 mb-4">
            <a href="{{route('courses.show', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">

            <div
                class="bg-slate-50 w-full rounded-xl divide-gray-400 divide-double mt-4">
                <div class="w-full">
                    <table class="my-4 table-auto w-11/12 mx-auto border-collapse border border-4 border-black">
                        @forelse($course->quizzes as $quiz)
                            <tr class="bg-slate-600 w-10/12 mx-auto mt-2 mb-2 text-gray-100 px-3 py-2 text-center">
                                <td class="text-lg font-bold text-left pl-6">
                                    <div>
                                        {{$loop->iteration}}. kvíz
                                        {{ $quiz->title }}
                                    </div>
                                </td>
                                <td>
                                    Modul: {{ $quiz->modules[0]->title }}
                                </td>
                                <td class="flex justify-evenly">
                                    @can('delete', $quiz)
                                        <form method="POST" action="{{route('quizzes.destroy', $quiz) }}">
                                            @method('DELETE')
                                            @csrf
                                            <button
                                                class="deleteQuizBtn bg-red-50 hover:bg-red-500 text-red-500 hover:text-red-50 border-4 border-red-500 hover:border-red-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-solid fa-trash fa-lg"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    @can('update', $quiz)
                                        <a href="{{ route('quizzes.edit', [$course, $quiz]) }}">
                                            <button
                                                class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </button>
                                        </a>
                                    @endcan
                                </td>
                                <td>
                                    <div>

                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('quizzes.show', $quiz) }}">
                                        <button
                                            class="text-2xl md:w-12 md:h-12 w-10 h-10 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>

                                <td colspan="2" class="text-center text-lg font-semibold italic py-4">
                                    A kurzushoz még nincsen modul elkészítve... talán később
                                </td>
                            </tr>
                        @endforelse
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
