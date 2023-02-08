@extends('layouts.app')
@section('title', 'Csoportok')
@section('content')
<div class="mt-16">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-10/12 md:w-8/12 w-11/12">
            <div class="lg:flex lg:justify-between mb-12 ">
                <div class="font-black text-3xl">Csoportok</div>
                <div class="w-fit">
                    <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg text-sm">
                        <a href="{{ route('groups.create') }}"><i class="fa-solid fa-circle-plus"></i> Csoport létrehozása</a>
                    </button>
                </div>
            </div>

            <div class="bg-slate-50 w-full rounded-xl">
                <div class="my-6 w-10/12 mx-auto">
                    <form>
                        <div class="flex">
                            <div class="form-group">
                                <label for="invCode" class="text-lg font-semibold">Kód:</label>
                                <input type="text" id="invCode" name="invCode" class="form-control border rounded font-medium italic text-md" placeholder="meghívó kód" required>
                            </div>
                            <div class="form-group ml-6">
                                <button id="sendInvCode" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-2 rounded-lg"><i class="fa-regular fa-paper-plane"></i> Jelentkezés</button>
                            </div>
                        </div>
                    </form>
                </div>
                @foreach($groups as $group)
                        <div class="bg-slate-600 w-10/12 mx-auto mb-4 text-gray-100 px-6 py-4 lg:flex lg:justify-between items-center">
                            <div class="lg:w-1/4 hover:underline text-lg font-bold">
                                <a href="{{ route('groups.show', $group->id) }}">
                                {{ $group->name }}
                                </a>
                            </div>
                            <div class="w-fit font-thin italic font-mono flex relative border rounded-md bg-slate-200">
                                <input type="text" value="{{ $group->invCode }}" size="15" disabled readonly class="border-none bg-transparent text-stone-600"/>
                                <button class="copyInvCode bg-slate-400 hover:bg-slate-700 px-2 border-none rounded-md" data-invCode="{{ $group->invCode }}"><i class="fa-regular fa-clone fa-lg"></i></button>
                            </div>
                            <div class="lg:text-right lg:w-1/4 w-fit font-medium relative">
                                @if($group->creator_id == Auth::id())
                                    <a href="{{ route('join_requests', $group->id) }}">
                                        <button type="button" class="relative inline-flex items-center p-2 text-md font-semibold text-center text-white border rounded-lg">
                                            Kérelmek
                                            <span class="sr-only">Kérelmek</span>
                                            <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">{{ $group->join_requests }}</div>
                                        </button>
                                    </a>
                                @endif
                            </div>
                            <button class="text-2xl w-16 h-16 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                <a href="{{ route('groups.show', $group->id) }}">
                                    <i class="fa-solid fa-angles-right"></i>
                                </a>
                            </button>
                        </div>
                @endforeach
            </div>
        </div>
</div>
<div class="absolute bottom-4 right-2" id="invCodeCopiedMessage" style="display: none;">
        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-2.5 rounded dark:bg-gray-700 dark:text-yellow-300 border border-yellow-300" id="copyMessage"></span>
</div>

@endsection
