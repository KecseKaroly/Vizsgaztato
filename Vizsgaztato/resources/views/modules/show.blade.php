@extends('layouts.app')
@section('title', 'A modulról')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('ckeditor/sample/styles.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('ckeditor/ckeditor.css')}}" />
@endsection
@section('content')
    <div class="mt-4">
        <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
            <a href="{{ route('courses.modules', $module->course) }}">
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
                        <a href="{{ route('quizzes.create', [$module->course, $module]) }}"><i class="fa-solid fa-circle-plus"></i> Kvíz hozzáadása</a>
                    </button>
                    @endcan
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl divide-y-4 divide-gray-400 divide-double mt-4">
                    <div class="prose" style="margin: auto;">
                        <textarea name="material" id="module-material-textarea">
                            {{ $module->material }}
                        </textarea>
                    </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('modules/ckeditor_img_upload', ['course_creator_id'=>-1])
@endpush
