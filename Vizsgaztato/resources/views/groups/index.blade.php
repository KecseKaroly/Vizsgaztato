@extends('layouts.app')
@section('title', 'Csoportok')
@section('scripts')
    @vite('resources/js/ajax.js')
@endsection
@section('content')
    <div class="mt-4">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <div class="lg:flex lg:justify-between mb-12 ">
                <div class="font-black text-3xl">Csoportok</div>
                <div class="w-fit mt-3">
                    @can('create', App\Models\group::class)
                        <button type="button"
                                id="createGroup"
                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                            <a href="{{ route('groups.create') }}"><i class="fa-solid fa-circle-plus"></i> Csoport
                                létrehozása</a>
                        </button>
                    @endcan
                </div>
            </div>

            <div class="bg-slate-50 w-full rounded-xl divide-y-4 divide-gray-400 divide-double">
                    <div class="my-6 w-10/12 mx-auto flex justify-between flex-wrap">
                        <form class="md:mb-0 mb-4">
                            <div class="md:flex">
                                <div class="form-group">
                                    <label for="invCode" class="text-lg font-semibold">Kód:</label>
                                    <input type="text" id="invCode" name="invCode"
                                           class="border border-indigo-800 focus:border-indigo-800 text-indigo-900 focus:bg-indigo-300 bg-indigo-200 rounded font-medium italic text-md "
                                           placeholder="Meghívó kód" autocomplete="off" required>
                                </div>
                                <div class="form-group md:ml-6">
                                    <button id="sendInvCode"
                                            class="md:mt-0 mt-1 hover:bg-indigo-800 bg-indigo-600 border-2 border-gray-100 text-white font-bold p-2 rounded-lg">
                                        <i class="fa-regular fa-paper-plane"></i> Jelentkezés
                                    </button>
                                </div>
                            </div>
                        </form>
                        <a class="nav-link" href="{{ route('inv_requests') }}">
                            <button type="button"
                                    class="relative inline-flex items-center p-2 text-md font-semibold text-center text-white border rounded-lg bg-cyan-600 hover:bg-cyan-900 ">
                                Meghívók
                                <span class="sr-only">Meghívók</span>
                                @if($inv_requests > 0)
                                    <div
                                        class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">{{ $inv_requests }}</div>
                                @endif
                            </button>
                        </a>
                    </div>
                <div class="w-full">

                    @forelse($groups as $group)
                        <div
                            class="bg-slate-600 w-10/12 mx-auto mt-4 mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center">
                            <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2">
                                <a href="{{ route('groups.show', $group->id) }}">
                                    {{ $group->name }}
                                </a>
                            </div>
                            <div
                                class="md:mt-0 my-2 font-thin italic font-mono flex justify-between relative border rounded-md bg-slate-200">
                                <input type="text" value="{{ $group->invCode }}" size="15" disabled readonly
                                       class="border-none bg-transparent text-stone-600"/>
                                <button class="copyInvCode bg-slate-400 hover:bg-slate-500 px-2 border-none rounded-md"
                                        data-invCode="{{ $group->invCode }}"><i class="fa-regular fa-clone fa-lg"></i>
                                </button>
                            </div>
                            <div class="md:mt-0 my-2 lg:text-right w-1/4 font-medium relative">
                                @if($group->creator_id == auth()->id())
                                    <a href="{{ route('join_requests', $group->id) }}">
                                        <button type="button"
                                                class="relative inline-flex items-center p-2 text-md font-semibold text-center text-white border rounded-lg hover:bg-slate-500 bg-slate-400">
                                            Kérelmek
                                            <span class="sr-only">Kérelmek</span>
                                            @if($group->join_requests > 0)
                                                <div
                                                    class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">{{ $group->join_requests }}</div>
                                            @endif
                                        </button>
                                    </a>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('groups.show', $group->id) }}">
                                    <button
                                        class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-lg font-semibold italic py-4">
                            @if(auth()->user()->auth)
                                Üresség... Még nem csatlakoztam egy csoportba sem
                            @else
                                Még nem hoztam létre csoportot.
                            @endif
                        </div>
                    @endforelse
                    {{ $groups->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
