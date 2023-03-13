@extends('layouts.app')
@section('title', 'A kurzus moduljai')
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{route('courses.show', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-4 ">
                <div class="font-black text-3xl">Kurzus ({{$course->title}}) moduljai</div>
                @can('create', [App\Models\Module::class, $course])
                    <button type="button"
                            class="hover:bg-green-700 bg-green-500 border-2 border-gray-100 text-white font-bold p-3.5 rounded-lg text-sm">
                        <a href="{{ route('modules.create', $course) }}"><i
                                class="fa-solid fa-circle-plus"></i>
                            Modul hozzáadása</a>
                    </button>
                @endcan
            </div>
            <div
                class="bg-slate-50 rounded-xl divide-gray-400 divide-double mt-4">

                <table class="my-4 table-auto w-11/12 mx-auto border-collapse border border-4 border-black">
                    @forelse($course->modules as $module)
                        <tr class="bg-slate-600 w-10/12 mx-auto mt-2 mb-2 text-gray-100 px-3 py-2 text-center">
                            <td class="text-lg font-bold text-left pl-6">
                                <div>
                                    {{$loop->iteration}}. modul
                                    {{ $module->title }}
                                </div>
                                <div class="text-sm font-light pl-12">Témakör: {{ $module->topic }}</div>
                            </td>
                            <td class="flex justify-evenly">
                                @can('delete', $module)
                                    <form method="POST" action="{{route('modules.destroy', $module) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button
                                            class="bg-red-50 hover:bg-red-500 text-red-500 hover:text-red-50 border-4 border-red-500 hover:border-red-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                            <i class="fa-solid fa-trash fa-lg"></i>
                                        </button>
                                    </form>
                                @endcan
                                @can('update', $module)
                                    <a href="{{ route('modules.edit', $module) }}">
                                        <button
                                            class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg  w-fit py-1.5 px-2 mt-1.5 mb-2.5">
                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                        </button>
                                    </a>
                                @endcan
                            </td>
                            <td>
                                <a href="{{ route('modules.show', $module) }}">
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
@endsection
