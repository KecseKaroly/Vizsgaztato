@extends('layouts.app')
@section('title', 'A modulról')
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 ml-12 mb-4">
            <a href="{{route('courses.show', $module->course)}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        </div>
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-4 ">
                <div class="flex flex-col">
                    <div class="font-black text-3xl">Modul: {{$module->title}}</div>
                    <div class="font-semibold italic pl-8">Témakör: {{$module->topic}}</div>
                </div>
                <div class="w-fit">
                    @can('create', [App\Models\Module::class, $module->course])
                    <button type="button"
                            class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                        <a href="#"><i class="fa-solid fa-circle-plus"></i> Kvíz hozzáadása</a>
                    </button>
                    @endcan
                </div>
            </div>
            @can('delete', $module)
                <div class="w-fit">
                    <form method="POST" action="{{route('modules.destroy', $module) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                                class="deleteCourseBtn bg-red-50 hover:bg-red-600 text-red-600 hover:text-red-50 border-4 border-red-600 hover:border-red-50 rounded-lg font-semibold text-lg flex items-center  w-full py-1.5 px-2">
                            <i class="fa-solid fa-trash-can fa-xl"></i> Modul Törlése
                        </button>
                    </form>
                    <a href="{{ route('modules.edit', $module) }}">
                        <button
                            class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg  w-full py-1.5 px-2 mt-1.5 mb-2.5">
                            <i class="fa-regular fa-pen-to-square fa-xl"></i> Modul Szerkesztése
                        </button>
                    </a>
                </div>
            @endif

            <div class="bg-slate-50 w-full rounded-xl divide-y-4 divide-gray-400 divide-double mt-4">
                <div class="w-full prose">
                    {!! $module->material !!}
                </div>

            </div>
        </div>
    </div>
@endsection
