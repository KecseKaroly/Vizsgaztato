@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-full">Teszthez rendelt csoportok</div>
        </div>
        <div class="bg-slate-50 w-11/12 rounded-xl">
            @foreach($test_groups as $test_group)
                <div id="test_group{{ $test_group->id }}" class="bg-red-300 mb-6">
                    <div>{{ $test_group->name }}</div>
                    <div>
                        <form>
                            @csrf
                            From: <input type="date" class="datePick" name="enabled_from" value="{{ $test_group->enabled_from }}">
                            Until: <input type="date" name="enabled_until" value="{{ $test_group->enabled_until}}">
                            <button type="submit" data-test_group_id="{{ $test_group->id }}" class="updateTestGroups">Mentés</button>
                        </form>
                    </div>
                    <div>
                        <form>
                            @csrf
                            @METHOD('DELETE')
                            <button type="submit" data-test_group_id="{{ $test_group->id }}" class="deleteTestGroups">Törlés</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
