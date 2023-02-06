@extends('layouts.app')
@section('content')
<div class="mt-24 mb-24 select-none">
    <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <div class="flex">
            <div class="text-center mb-12 font-black text-3xl w-10/12">Kitöltött feladatlapok</div>
            <div class="w-2/12 pb-5">
                <div class="w-48 ml-auto">
                    <button type="button" class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                        <a href="{{ route('showTestGroups', $test->id) }}">Csoportok kezelése</a>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:flex">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">
                @foreach($groups as $group)
                    <div>
                        <div  class="group_id hover:bg-blue-300" data-group_id="{{ $group->id }}" style="display: block;">
                            {{ $group->name }}
                        </div>
                        <div id="attempts_of_group{{ $group->id }}" style="display: none;">
                            @foreach($groups_users as $group_user)
                                @if($group_user->group_id == $group->id)
                                    @foreach($users as $user)
                                    @if($user->id == $group_user->user_id)
                                        <div class="user_id" data-user_id="{{ $user->id }}" data-group_id="{{ $group->id }}">
                                            <div class="hover:bg-blue-300 ml-5">
                                                {{ $user->name }}
                                            </div>
                                            <div id="attempts_of_user{{ $user->id }}_{{ $group->id }}" style="display: none;">
                                                @foreach ($testAttempts as $testAttempt )
                                                    @if($testAttempt->user_id == $user->id)
                                                        <div class="hover:bg-blue-300 ml-5 pl-5">
                                                            <a href="{{ route('checkAttemptResult', [$test->id, $testAttempt->id]) }}">test#{{ $testAttempt->id }}</a>
                                                            {{ $testAttempt->achievedScore }}/{{ $testAttempt->maxScore }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
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
</div>
@endsection
