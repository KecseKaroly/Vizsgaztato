@extends('layouts.app')
@section('title', 'Modul módosítása')
@section('scripts')
    @include('modules.tinymc_editable')
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
            <div class="flex">
                <div class="text-center mb-12 font-black text-3xl w-10/12">Modul módosítása</div>
            </div>
            <div class="bg-slate-50 rounded-xl">
                @if($errors->any())
                    <div class="text-red-600 text-center divide-y-2">
                        <h1 class="text-xl font-black">Hiba</h1>
                        <ol>
                            @foreach($errors->all() as $error)
                                <li class="ml-3 italic">{{$error}}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                <form method="POST" action="{{ route('modules.update', $module) }}">
                    @csrf
                    @method('PUT')
                    <div class="md:flex md:items-center mb-6 mt-10">
                        <div class="md:w-2/5">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3"
                                   for="title">
                                Modul címe
                            </label>
                        </div>
                        <div class="md:w-3/5">
                            <input
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                id="title" type="text" name="title" value="{{$module->title}}" required autofocus>
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-6 mt-10">
                        <div class="md:w-2/5">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-8 md:mx-0 mx-3"
                                   for="topic">
                                Modul témaköre
                            </label>
                        </div>
                        <div class="md:w-3/5">
                            <input
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded md:w-8/12 w-11/12 py-3 md:mx-0 mx-3 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                id="topic" type="text" name="topic" value="{{$module->topic}}" required>
                        </div>
                    </div>
                    <div class="mb-6 mt-10 px-32">
                        <textarea name="material" id="myeditorinstance">{{ $module->material }}</textarea>
                    </div>
                    <div class="md:flex md:items-center mb-10 md:mx-0 mx-3">
                        <div class="md:w-2/5"></div>
                        <div class="md:w-3/5">
                            <button
                                class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                type="submit">
                                {{ __('Módosítás') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('modules/ckeditor_img_upload', ['course_creator_id'=>$module->course->creator_id])
@endpush
