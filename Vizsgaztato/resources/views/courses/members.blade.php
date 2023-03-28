@extends('layouts.app')
@section('title', 'A kurzus tagjai')
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
            <div class="font-black text-3xl my-4">Kurzus ({{$course->title}}) tagjai</div>
            <div
                class="bg-slate-50 w-full md:flex rounded-xl md:divide-x-4 md:divide-y-0 divide-y-4 divide-gray-400 divide-double mt-4">
                <div class="md:w-1/2 w-full">
                    <div class="sm:flex sm:justify-between">
                        <div class="mt-4 px-6 text-2xl font-semibold">Tagok:</div>
                        <div class="mr-8">
                            @can('create', [App\Models\Module::class, $course])
                                    <button data-modal-target="courseUsers"
                                            data-modal-toggle="courseUsers"
                                            class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-2.5 rounded-lg my-2.5 ml-4">
                                        Diák hozzáadása
                                    </button>
                            @endcan
                        </div>
                    </div>
                    <table class="table-auto w-11/12 mx-auto mb-4 border-collapse border border-4 border-black">
                        @forelse($course->users as $user)
                            <tr @class([
                                        "border-b mx-auto text-lg text-center",
                                        $user->id == auth()->id() ? 'bg-slate-400  text-grey-400 italic' : "bg-slate-600  text-gray-200",
                                        ]) id="course_user-{{$user->pivot->id}}">
                                <td>
                                    @if($course->creator_id == $user->id)
                                        <div class="ml-2 pr-5"><i class="fa-solid fa-user-graduate fa-lg"></i></div>
                                    @else
                                        <div class="ml-2 pr-5"><i class="fa-solid fa-user fa-lg"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <div class="pl-5">{{ $user->name }}</div>
                                </td>
                                <td>@if($course->creator_id == auth()->id() && $user->id != auth()->id())
                                        <div class="py-1 px-5 text-sm">
                                            <form method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="removeUserFromCourse"
                                                        data-id="{{$user->pivot->id}}">
                                                <span class="fa-stack fa-2x" style="font-size: 1.25em;">
                                                    <i class="fa-solid fa-user  fa-stack-1x"></i>
                                                    <i class="fa-solid fa-ban fa-stack-2x" style="color:red"></i>
                                                </span>
                                                </button>
                                            </form>
                                        </div>
                                    @elseif ($user->id == auth()->id() && $course->creator_id != auth()->id())
                                        <form method="POST" action="{{route('leaveFromCourse', $user->pivot->id)}}">
                                            @csrf
                                            @METHOD('DELETE')
                                            <button type="submit" class="px-5 py-1 text-xl text-red-700">
                                                <i class="leaveFromCourse fa-solid fa-arrow-right-from-bracket"></i>
                                            </button>
                                        </form>
                                        </a>
                                    @else
                                        <div class="px-5 py-1 text-xl">
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <div class="text-center text-lg font-semibold italic py-4">
                                A kurzushoz még nincsen tag rendelve
                            </div>
                        @endforelse
                    </table>

                </div>
                <div class="md:w-1/2 w-full">
                    <div class="sm:flex sm:justify-between">
                        <div class="mt-4 px-6 text-2xl font-semibold">Csoportok:</div>
                        <div class="mr-8">
                            @can('create', [App\Models\Module::class, $course])

                                <button data-modal-target="addGroups"
                                        data-modal-toggle="addGroups"
                                        class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-2.5 rounded-lg my-2.5 ml-4">
                                    Csoportok kezelése
                                </button>
                            @endcan
                        </div>
                    </div>

                    <table class="table-auto w-11/12 mx-auto mb-4 border-collapse border border-4 border-black">
                        @forelse($course->groups as $group)
                            <tr class="border-y mx-auto text-lg bg-slate-600 text-stone-200">
                                <td class="pl-10">{{$group->name}}</td>

                                <td class="text-center"> <button data-group_id="{{ $group->id }}"
                                             class="group_id hover:cursor-pointer hover:bg-slate-800  text-2xl md:w-10 md:h-10 w-8 h-8 rounded-full bg-slate-400  text-white">
                                        <i class="fa-solid fa-angles-down" id="arrow_of_group{{ $group->id }}"></i>
                                    </button></td>
                            </tr>
                            <tr id="toggle_group{{$group->id}}"  class="border-y mx-auto text-lg">
                                <td class="bg-slate-400" colspan="2">
                                    <table class="w-full">
                                        @forelse($group->users as $user)
                                            <tr>
                                                <td>
                                                    <div class="ml-16 flex text-center">
                                                        <div class="ml-2 pr-5"><i class="fa-solid fa-user fa-lg"></i></div>
                                                        <div class="pl-5">{{ $user->name }}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>
                                                    <div class="text-center text-lg font-semibold italic py-1">
                                                        A csoporthoz nincsen még ember rendelve...
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforelse
                                    </table>
                                </td>
                            </tr>
                        @empty
                            <div class="text-center text-lg font-semibold italic py-4">
                                A kurzushoz még nincsen csoport rendelve...
                            </div>
                        @endforelse
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div id="courseUsers" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Hozzárendelt felhasználók...
                    </h3>
                    <button data-modal-hide="courseUsers" type="button"
                            class="text-gray-400 bg-transparent hover:bg-red-500 hover:text-white rounded-lg p-1.5">
                        <i class="fa-solid fa-xmark fa-2xl"></i>
                        <span class="sr-only">Bezárás</span>
                    </button>
                </div>
                @livewire('search-users', ['objectToAttachTo'=>$course, 'objectType'=>'course'])
            </div>
        </div>
    </div>

    <div id="addGroups" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Hozzárendelt csoportok...
                    </h3>
                    <button data-modal-hide="addGroups" type="button"
                            class="text-gray-400 bg-transparent hover:bg-red-500 hover:text-white rounded-lg p-1.5">
                        <i class="fa-solid fa-xmark fa-2xl"></i>
                        <span class="sr-only">Bezárás</span>
                    </button>
                </div>
                @livewire('search-groups', ['currentGroups'=>$course->groups, 'objectToAttachTo'=>$course, 'objectType'=>'course'])
            </div>
        </div>
    </div>
@endsection
