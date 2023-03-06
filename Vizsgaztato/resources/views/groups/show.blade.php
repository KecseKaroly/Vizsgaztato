@extends('layouts.app')
@section('title',  $group->name )
@section('content')
    <div class="mt-16  mx-auto">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-10/12 md:w-8/12 w-11/12">
            <div class="lg:flex lg:justify-between mb-12">
                <div class="font-black text-3xl">Csoport: {{ $group->name }}</div>
            </div>
            @if($isAdmin)
                <div class="w-fit">
                    <form method="POST" action="{{route('groups.destroy', $group->id) }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                                class="deleteGroupBtn bg-red-50 hover:bg-red-600 text-red-600 hover:text-red-50 border-4 border-red-600 hover:border-red-50 rounded-lg font-semibold text-lg flex items-center  w-full py-1.5 px-2">
                            <i class="fa-solid fa-trash-can fa-xl"></i> Csoport Eltávolítása
                        </button>
                    </form>
                    <a href="{{ route('groups.edit', $group->id) }}">
                        <button
                            class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg  font-semibold text-lg  w-full py-1.5 px-2 mt-1.5 mb-2.5">
                            <i class="fa-regular fa-pen-to-square fa-xl"></i> Csoport Szerkesztése
                        </button>
                    </a>
                </div>
            @endif
            <div class="bg-slate-50 w-full rounded-xl  mx-auto">
                @if($isAdmin)
                    <div class="w-10/12 mx-auto my-5">
                        @livewire('search-users', ['groupId'=>$group->id])
                    </div>
                @endif
                <div class="w-11/12 mx-auto pt-5 text-2xl font-bold underline">Csoport tagjai:</div>
                <div class="divide-y-4 flex flex-col">
                    @foreach($group->users as $user)

                        <div class="w-10/12 mx-auto text-gray-100  py-5 flex" id="group_user-{{ $user->pivot->id }}">
                            <div class="bg-slate-600 w-full px-3 flex justify-between content-start text-center">
                                <div class="divide-x-2 flex text-xl py-3">
                                    @if($user->pivot->is_admin)
                                        <div class="ml-2 pr-5"><i class="fa-solid fa-user-graduate fa-lg"></i></div>
                                    @else
                                        <div class="ml-2 pr-5"><i class="fa-solid fa-user fa-lg"></i></div>
                                    @endif
                                    <div class="pl-5">{{ $user->name }}</div>
                                </div>

                                @if( $isAdmin && !$user->pivot->is_admin)
                                    <div class="py-2  px-5">
                                        <form method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="removeUserFromGroup"
                                                    data-id="{{ $user->pivot->id }}">
                                                <span class="fa-stack fa-2x" style="font-size: 1.25em;">
                                                    <i class="fa-solid fa-user  fa-stack-1x"></i>
                                                    <i class="fa-solid fa-ban fa-stack-2x" style="color:red"></i>
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                @elseif ($user->id == auth()->id() && $group->creator_id != auth()->id())
                                    <form method="POST" action="{{ route('leaveUserFromGroup', $user->pivot->id) }}">
                                        @csrf
                                        @METHOD('DELETE')
                                        <button type="submit" class="px-5 py-3">
                                            <i class="leaveFromGroup fa-solid fa-arrow-right-from-bracket fa-2xl"></i>
                                        </button>
                                    </form>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
