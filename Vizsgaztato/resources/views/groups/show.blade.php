@extends('layouts.app')
@section('title',  $group->name )
@section('content')
<div class="mt-16">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-10/12 md:w-8/12 w-11/12">
        <div class="lg:flex lg:justify-between mb-12">
            <div class="font-black text-3xl">Csoport: {{ $group->name }}</div>
        </div>
        <div>
            <form method="POST" action="{{route('groups.destroy', $group->id) }}"  >
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-50 hover:bg-red-600 text-red-600 hover:text-red-50 border-4 border-red-600 hover:border-red-50 rounded-lg  p-3 font-semibold text-lg w-1/4 flex items-center pl-14"><div><i class="fa-solid fa-trash-can fa-xl"></i></i> Csoport Eltávolítása</div></button>
            </form>
            <button class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-300 hover:border-yellow-50 rounded-lg my-4 p-3 font-semibold text-lg w-3/12 flex pl-14 "><a href="{{ route('groups.edit', $group->id) }}"><i class="fa-regular fa-pen-to-square fa-xl"></i> Csoport Szerkesztése</a></button>
        </div>

        <div class="bg-slate-50 w-11/12 rounded-xl">
            @if($myRole === 'admin')
            <div>
               @livewire('search-users', ['groupId'=>$group->id])
            </div>
            @endif
            @foreach($members as $member)

                    <div class="bg-slate-600 w-10/12 mx-auto my-2 text-gray-100 px-3">
                        <div>{{ $member->name }}</div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
