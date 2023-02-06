@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-10/12">Csoport</div>
        </div>
        <div>
            <!--<form method="POST" action="{{route('groups.destroy', $group->id) }}"  >
                @method('DELETE')
                @csrf
                <button type="submit">Csoport törlése</button>
            </form>
            <a href="{{ route('groups.edit', $group->id) }}"><div>Szerkesztés</div></a>-->
        </div>

        @if($myRole === 'admin')
        <div>
           @livewire('search-users', ['groupId'=>$group->id])
        </div>
        @endif
        <div class="bg-slate-50 w-11/12 rounded-xl">
            @foreach($members as $member)

                    <div class="bg-slate-600 w-10/12 mx-auto my-2 text-gray-100 px-3">
                        <div>{{ $member->name }}</div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
