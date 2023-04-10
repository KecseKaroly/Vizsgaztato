@extends('layouts.app')
@section('title', 'A kurzusról')
@section('content')
    <div class="mt-4 pb-6">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
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
            <div class="absoulte">
                <div class="grid grid-cols-4 bg-stone-100 relative top-10 right-0 left-0 md:mx-32 mx-1 border-4 border-gray-800 rounded-full
                           text-center content-center divide-x-4 divide-gray-800 divide-dashed">
                    <a href="{{route('courses.members', $course)}}" class="md:py-8 py-4 block hover:bg-stone-200 hover:rounded-l-full">
                        <div class="md:text-xl font-semibold">Tagok</div>
                        <div class="md:text-xs text-2xs">{{$course->users->count()}} fő+{{$course->groups->count()}} csoport</div>
                    </a>
                    <a href="{{route('courses.modules', $course)}}" class="md:py-8 py-4 block hover:bg-stone-200">
                        <div class="md:text-xl font-semibold">Modulok</div>
                        <div class="md:text-xs text-2xs">{{$course->modules->count()}} db</div>
                    </a>
                    <a href="{{route('quizzes.index', $course)}}" class="md:py-8 py-4 block hover:bg-stone-200">
                        <div class="md:text-xl font-semibold">Kvízek</div>
                        <div class="md:text-xs text-2xs">{{$course->quizzes->count()}} db</div>
                    </a>
                    <a href="{{route('test.index', $course)}}" class="md:py-8 py-4 block hover:bg-stone-200 hover:rounded-r-full">
                        <div class="md:text-xl font-semibold text-xs">Vizsga feladatsorok</div>
                        <div class="md:text-xs text-2xs">{{$course->exams->count()}} db</div>
                    </a>
                </div>
                <div
                    class="bg-slate-50 rounded-xl mt-4 h-fit">
                    <div class="w-full md:flex  md:divide-x-8
                    md:divide-gray-800 md:divide-double">
                        <div class="md:w-1/2 w-full">
                            <div
                                class="px-6 py-8 mb-4 text-2xl underline font-semibold bg-gray-400 border-4 md:border-r-0 border-gray-800">
                                A kurzus célja:
                            </div>
                            <div class="text-lg italic  px-12 text-justify overflow-y-auto h-64 break-words">
                                {{$course->goal}}
                            </div>
                        </div>
                        <div class="md:w-1/2 w-full md:mt-0 mt-6">
                            <div
                                @class([
                                         "md:flex justify-between px-6 mb-4 text-2xl bg-gray-400 border-4 md:border-l-0 border-gray-800 items-center",
                                         auth()->id() == $course->creator_id ? "py-7" : "py-8",
                                        ])>
                                <div class="underline font-semibold">Modulok:</div>
                                <div class="w-fit md:mt-0 mt-2">
                                    @can('create', [App\Models\Module::class, $course])
                                        <button type="button"
                                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-2 rounded-lg text-sm">
                                            <a href="{{ route('modules.create', $course) }}"><i
                                                    class="fa-solid fa-circle-plus"></i>
                                                Modul hozzáadása</a>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                            <table class="table-auto w-11/12 mx-auto  border-separate border border border-black border-spacing-y-4 bg-slate-500">
                                @if( count($course->modules) )
                                    <tr class="hover:cursor-pointer bg-slate-500 w-10/12 mx-auto mt-2 mb-2 text-gray-100 px-3 py-2 text-center">
                                        <td colspan="2" class="text-sm font-semibold text-left pl-24 text-right pr-8">
                                            <a href="{{route('courses.modules', $course)}}">
                                                << Minden modul megtekintése >>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                                @forelse($course->modules->take(4) as $module)
                                    <tr class="hover:cursor-pointer bg-slate-600 w-10/12 mx-auto mt-2 mb-2 text-gray-100 px-3 py-2 text-center"
                                        onclick="window.location='{{ route('modules.show', $module) }}';">
                                        <td class="text-lg font-bold text-left pl-6">
                                            <div>
                                                {{$loop->iteration}}. modul
                                                {{ $module->title }}
                                            </div>
                                            <div class="text-sm font-light pl-12">Témakör: {{ $module->topic }}</div>
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

        </div>

    </div>
@endsection
