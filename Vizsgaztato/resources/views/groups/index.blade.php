@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-9/12">Csoportok</div>
            <div class="w-3/12 pb-5">
                <div class="w-48 ml-auto">
                    <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                        <a href="{{ route('groups.create') }}">Csoport létrehozása</a>
                    </button>
                </div>
            </div>
        </div>
        <div>
            <div>Kód:</div>
            <form >

                <div class="form-group">
                    <label>invCode:</label>
                    <input type="text" name="invCode" class="form-control" placeholder="Name" required="">
                </div>

                <div class="form-group">
                    <button class="btn btn-success btn-submit">Jelentkezés</button>
                </div>

            </form>
        </div>
        <div class="bg-slate-50 w-11/12 rounded-xl">
            @foreach($groups as $group)

                    <div class="bg-slate-600 w-10/12 mx-auto my-2 text-gray-100 px-3">
                        <div>{{ $group->name }}</div>
                        <div>{{ $group->invCode }}</div>
                        @if($group->creator_id == Auth::id())
                            <a href="{{ route('join_requests', $group->id) }}">
                                <div>
                                    Jelentkezési kérelmek száma: {{ $group->join_requests }}
                                </div>
                            </a>
                        @endif
                        <div>
                            <a href="{{ route('groups.show', $group->id) }}">
                                <i class="fa-solid fa-angles-right"></i></a>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
