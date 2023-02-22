@extends('layouts.app')
@section('title', 'Kitöltött feladatlapok')
@section('content')

    <div class="mt-24 mb-24 select-none">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hiddenlg:w-4/6 md:w-8/12 w-11/12">
            <div class="md:flex  md:justify-between ">
                <div class="text-center mb-12 font-black text-3xl italic">Kitöltött feladatlapok</div>
                <div class="pb-5">
                    <div class="w-48 ml-auto">
                        <button data-modal-target="testGroups"
                                data-modal-toggle="testGroups"
                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                            Csoportok kezelése
                        </button>
                    </div>
                </div>
            </div>
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 text-gray-100">
                @if(isset($noAttempts))
                    Még nincsen kitöltve
                @else
                    @foreach($test->groups as $group)
                        <div class="my-6">
                            <div class="bg-slate-600 rounded-lg">
                                <div class=" flex w-full justify-between text-2xl py-2 px-2 rounded-md">
                                    <div class="flex divide-x-2">
                                        <div class="px-4"><i class="fa-solid fa-users"></i></div>
                                        <div class="px-4 font-bold">{{ $group->name }}</div>
                                    </div>
                                    <div>
                                        <button data-modal-target="testGroupDetails"
                                                data-modal-toggle="testGroupDetails"
                                                data-group_id="{{ $group->id }}"
                                                data-test_id="{{ $test->id }}"

                                                data-enabled_from="{{ $group->pivot->enabled_from }}"
                                                data-enabled_until="{{ $group->pivot->enabled_until }}"
                                                class="showGroupTestInfo
                                                {{ $group->pivot->enabled_from < now() &&
                                                   $group->pivot->enabled_until > now() ?
                                                        "bg-green-500" :
                                                        "bg-red-500"}}
                                                   p-1 rounded text-lg">
                                            <i class="fa-solid {{ $group->pivot->enabled_from < now() &&
                                                   $group->pivot->enabled_until > now() ?
                                                        "fa-eye" :
                                                        "fa-eye-slash"}}"></i>
                                            {{ $group->pivot->enabled_from < now() &&
                                                   $group->pivot->enabled_until > now() ?
                                                        "Aktív" :
                                                        "Inaktív"}}
                                        </button>
                                    </div>
                                    <div class="pr-6">
                                        <button data-group_id="{{ $group->id }}"
                                                class="group_id hover:cursor-pointer hover:bg-slate-800  text-2xl md:w-10 md:h-10 w-6 h-6 rounded-full bg-slate-400  text-white">
                                            <i class="fa-solid fa-angles-down" id="arrow_of_group{{ $group->id }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="attempts_of_group{{ $group->id }}" style="display: none;">
                                @forelse($group->users as $user)
                                    <div
                                        class="bg-slate-500 rounded-md flex w-11/12 justify-between text-xl font-semibold py-2 px-3 ml-16">
                                        <div class="flex divide-x-2">
                                            <div class="px-4"><i class="fa-solid fa-user"></i></div>
                                            <div class="px-4 flex font-semibold">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                        <div class="pr-6">
                                            <button data-user_id="{{ $user->id }}"
                                                    data-group_id="{{ $group->id }}"
                                                    class="user_id hover:cursor-pointer hover:bg-slate-800  text-2xl md:w-10 md:h-10 w-6 h-6 rounded-full bg-slate-400  text-white">
                                                <i class="fa-solid fa-angles-down"
                                                   id="arrow_of_user{{ $user->id }}_{{ $group->id }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="attempts_of_user{{ $user->id }}_{{ $group->id }}"
                                         style="display: none;">
                                        @forelse($user->attempts as $testAttempt )
                                            <div id="testAttempt#{{ $testAttempt->id }}"
                                                 class="hover:bg-blue-400 bg-slate-400 rounded-md flex items-center w-10/12 justify-between text-base px-12 py-2 ml-32">
                                                <div>
                                                    <a href="{{ route('checkAttemptResult', $testAttempt->id) }}"  target="_blank">
                                                        <button
                                                            class="bg-lime-500 hover:bg-lime-300 p-1.5 border rounded-lg text-lime-900 ">
                                                            <i class="fa-solid fa-eye"></i> Megtekint
                                                        </button>
                                                    </a>
                                                </div>
                                                <div>
                                                    {{ $testAttempt->achievedScore }}
                                                    /{{ $testAttempt->maxScore }}
                                                </div>
                                                <div>
                                                    Kitöltve: {{ $testAttempt->created_at }}
                                                </div>
                                                <div>
                                                    <form>
                                                        <button data-id="{{ $testAttempt->id }}"
                                                                class="deleteTestAttempt rounded-full md:w-10 md:h-10 w-6 h-6 bg-red-600 hover:bg-red-800">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div

                                                class="hover:bg-blue-400 bg-slate-400 rounded-md flex items-center w-10/12 justify-between text-xl px-12 py-3 ml-32">
                                                <div>
                                                    Még nincsen kitöltött tesztje...
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                @empty
                                    <div
                                        class="bg-slate-500 rounded-md flex w-11/12 justify-between text-xl font-semibold py-3 px-3 ml-16">
                                        <div class="flex divide-x-2">
                                            <div class="px-4 flex font-semibold">
                                                Úgy tűnik, üres a csoport...
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div id="testGroupDetails" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <form method="POST" action=" {{ route('updateTestGroup') }}">
                    @csrf
                    @method('PUT')
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Érvényesség kezelése...
                        </h3>
                        <button data-modal-hide="testGroupDetails" type="button"
                                class="text-gray-400 bg-transparent hover:bg-red-500 hover:text-white rounded-lg p-1.5">
                            <i class="fa-solid fa-xmark fa-2xl"></i>
                            <span class="sr-only">Bezárás</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-6 flex flex-col">
                        <div>Aktív:</div>
                        <div class="flex justify-around">
                            <input type="hidden" name="test_id" id="test_id">
                            <input type="hidden" name="group_id" id="group_id">
                            <div><input type="datetime-local" name="enabled_from" id="enabled_from">-től</div>
                            <div><input type="datetime-local" name="enabled_until" id="enabled_until">-ig</div>
                        </div>
                    </div>
                    <div class="flex items-center p-6 justify-evenly border-t-2">
                        <button type="submit"
                                class="border rounded bg-green-500 hover:bg-green-700 text-white p-2">Mentés
                        </button>
                        <button data-modal-hide="testGroupDetails" type="button"
                                class="border rounded bg-transparent hover:bg-stone-300 text-black p-2 ">Mégsem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="testGroups" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-2xl md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Hozzárendelt csoportok...
                    </h3>
                    <button data-modal-hide="testGroups" type="button"
                            class="text-gray-400 bg-transparent hover:bg-red-500 hover:text-white rounded-lg p-1.5">
                        <i class="fa-solid fa-xmark fa-2xl"></i>
                        <span class="sr-only">Bezárás</span>
                    </button>
                </div>
                @livewire('search-groups', ['currentGroups'=>$test->groups, 'test'=>$test])
            </div>
        </div>
    </div>
@endsection
