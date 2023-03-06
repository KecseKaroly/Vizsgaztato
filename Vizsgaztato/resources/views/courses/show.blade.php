@extends('layouts.app')
@section('title', 'A kurzusról')
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 ml-12 mb-4">
            <a href="{{route('courses.index')}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-4 ">
                <div class="font-black text-3xl">Kurzus: {{$course->title}}</div>
                <div class="w-fit">
                    @can('create', [App\Models\Module::class, $course])
                        <button type="button"
                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                            <a href="{{ route('modules.create', $course) }}"><i class="fa-solid fa-circle-plus"></i>
                                Modul hozzáadása</a>
                        </button>
                    @endcan
                </div>
            </div>
            @can('delete', $course)
                <div class="w-fit flex">
                    <form method="POST" action="{{route('courses.destroy', $course) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                                class="deleteCourseBtn bg-red-50 hover:bg-red-600 text-red-600 hover:text-red-50 border-4 border-red-600 hover:border-red-50 rounded-lg font-semibold text-lg flex items-center  py-1.5 px-2">
                            <i class="fa-solid fa-trash-can fa-xl"></i> Kurzus Törlése
                        </button>
                    </form>
                    <a href="{{ route('courses.edit', $course) }}">
                        <button type="submit"
                                class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg py-1.5 px-2 ml-3">
                            <i class="fa-regular fa-pen-to-square fa-xl"></i> Kurzus Szerkesztése
                        </button>
                    </a>
                </div>
            @endif
            <div class="md:w-1/2 w-full">
                <div class="mt-4 mb-4 px-6 text-2xl font-semibold"><a
                        href="{{route('courses.members', $course)}}">Tagok</a></div>
            </div>
            <div
                class="bg-slate-50 rounded-xl mt-4">

                <div class="w-full">
                    <div class="mt-4 mb-4 px-6 text-2xl font-semibold">Modulok:</div>
                    <table class="table-auto w-11/12 mx-auto mb-4 border-collapse border border-4 border-black">
                        @forelse($course->modules as $module)
                                <tr class="hover:cursor-pointer bg-slate-600 w-10/12 mx-auto mt-2 mb-2 text-gray-100 px-3 py-2 text-center"  onclick="window.location='{{ route('modules.show', $module) }}';">
                                    <td class="text-lg font-bold text-left pl-24">

                                            {{ $module->title }}

                                        <div class="text-sm font-light pl-6">Témakör:{{ $module->topic }}</div>
                                    </td>
                                    <td>
                                            <button
                                                class="text-2xl md:w-10 md:h-10 w-8 h-8 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                                <i class="fa-solid fa-angles-right"></i>
                                            </button>
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
