@extends('layouts.app')
@section('title', 'Kitöltött feladatlapok')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hiddenlg:w-4/6 md:w-8/12 w-11/12">
        <div class="md:flex  md:justify-between ">
            <div class="text-center mb-12 font-black text-3xl italic">Kitöltött feladatlapok</div>
            <div class="pb-5">
                <div class="w-48 ml-auto">
                    <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                        <a href="{{ route('showTestGroups', $test->id) }}">Csoportok kezelése</a>
                    </button>
                </div>
            </div>
        </div>
        <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2">
            @foreach($groups as $group)
                <div class="my-6">
                    <div class="bg-slate-600 rounded-lg">
                        <div class="group_id hover:bg-blue-400 flex w-full justify-between text-2xl hover:cursor-pointer py-2 px-2 rounded-md" data-group_id="{{ $group->id }}">
                            <div class="font-bold hover:underline">{{ $group->name }}</div>
                            <div class="pr-6"><i class="fa-solid fa-angles-down" id="arrow_of_group{{ $group->id }}"></i></div>
                        </div>
                    </div>

                    <div id="attempts_of_group{{ $group->id }}" style="display: none;">
                            @foreach($groups_users as $group_user)
                                @if($group_user->group_id == $group->id)
                                    @foreach($users as $user)
                                    @if($user->id == $group_user->user_id)
                                        <div class="user_id bg-slate-500" data-user_id="{{ $user->id }}" data-group_id="{{ $group->id }}">
                                            <div class="hover:bg-blue-400 rounded-md flex w-full justify-between text-lg hover:cursor-pointer py-2 px-3 pl-12">
                                                <div class="flex font-semibold hover:underline">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="pr-6"><i class="fa-solid fa-angles-down" id="arrow_of_user{{ $user->id }}_{{ $group->id }}"></i></div>
                                            </div>
                                        </div>
                                        <div id="attempts_of_user{{ $user->id }}_{{ $group->id }}" style="display: none;">
                                            @foreach ($testAttempts as $testAttempt )
                                                @if($testAttempt->user_id == $user->id)
                                                <div class="bg-slate-400">
                                                    <div class="hover:bg-blue-400 rounded-md flex w-full justify-between text-lg hover:cursor-pointer pl-24 px-6 py-1">
                                                        <div class="mr-12">
                                                            <a href="{{ route('checkAttemptResult', [$test->id, $testAttempt->id]) }}">test#{{ $testAttempt->id }}</a>
                                                        </div>
                                                        <div class="mr-12">
                                                            {{ $testAttempt->achievedScore }}/{{ $testAttempt->maxScore }}
                                                        </div>
                                                        <div>
                                                            Dátum: {{ $testAttempt->created_at }}
                                                        </div>
                                                    </div>
                                                </div>

                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @endforeach
                                @endif
                            @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
