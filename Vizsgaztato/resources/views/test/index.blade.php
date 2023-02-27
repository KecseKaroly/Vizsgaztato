@extends('layouts.app')
@section('title', 'Vizsga feladatsorok')
@section('content')
    <div class="mt-16 pb-24">
        <div class="flex flex-col max-w-full mx-auto rounded-xl overflow-hidden lg:w-10/12 md:w-8/12 w-11/12">
            <div class="lg:flex lg:justify-between mb-12">
                <div class="font-black text-3xl">Vizsga feladatsorok</div>
                <div class="w-fit">
                    @can('create', App\Models\test::class)
                        <button type="button"
                                class="hover:bg-green-700 bg-green-500 border-2 border-gray-100  text-white font-bold p-3.5 rounded-lg">
                            <a href="{{ route('test.create') }}">Új vizsgasor</a>
                        </button>
                    @endcan
                </div>
            </div>
            <div class="md:flex">
                <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border shadow-xl pb-8">
                    @can('create', App\Models\test::class)
                        @forelse($tests as $test)
                            <div
                                class="bg-slate-600 w-10/12 mx-auto mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center rounded mt-5">
                                <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2 w-fit">
                                    <a href="{{ route('checkTestInfo', [$test->id]) }}">
                                        {{$test->title}}
                                    </a>
                                </div>
                                @if($test->creator_id == auth()->id())
                                    <div class="w-3/12 flex justify-around">
                                        <a href="{{ route('test.edit', $test->id) }}">
                                            <button
                                                class="bg-yellow-50 hover:bg-yellow-300 text-yellow-300 hover:text-yellow-50 border-4 border-yellow-200 hover:border-yellow-50 rounded-lg  font-semibold text-lg md:w-fit w-full py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-regular fa-pen-to-square fa-xl"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('test.destroy', $test) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="deleteTestBtn bg-red-50 hover:bg-red-500 text-red-500 hover:text-red-50 border-4 border-red-400 hover:border-red-50 rounded-lg  font-semibold text-lg md:w-fit w-full py-1.5 px-2 mt-1.5 mb-2.5">
                                                <i class="fa-solid fa-trash fa-xl"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <a href="{{ route('checkTestInfo', [$test->id]) }}">
                                        <button
                                            class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>
                                @else
                                    <div></div>
                                    <a href="{{ route('testAttempts.index', [$test->id, $group->id]) }}">
                                        <button
                                            class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-lg font-semibold italic pt-5">
                                Még nem hoztam létre tesztet...
                            </div>
                        @endforelse
                    @else
                        @forelse($groups as $group)
                            <div class="my-8 text-3xl font-bold underline italic">{{$group->name}} tesztjei:</div>
                            @foreach($group->tests as $test)
                                <div
                                    class="bg-slate-600 w-10/12 mx-auto mb-4 text-gray-100 px-6 py-4 flex flex-wrap justify-between items-center rounded mt-5">
                                    <div class="lg:w-1/4 hover:underline text-lg font-bold md:mt-0 my-2 w-fit">
                                        <a href="{{ route('testAttempts.index', [$test->id, $group->id]) }}">
                                            {{$test->title}}
                                        </a>
                                    </div>
                                    @if(isset($test->pivot->enabled_from) && isset($test->pivot->enabled_until))
                                        <div>
                                            {{Carbon\Carbon::parse($test->pivot->enabled_from)->format('Y.m.d H:i:s')}}
                                            - {{Carbon\Carbon::parse($test->pivot->enabled_until)->format('Y.m.d H:i:s')}}
                                        </div>
                                        @if($test->pivot->enabled_from < now() && $test->pivot->enabled_until > now())
                                            <a href="{{ route('testAttempts.index', [$test, $group]) }}">
                                                <button
                                                    class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-slate-400 hover:bg-slate-500 text-white"
                                                    >
                                                <i class="fa-solid fa-angles-right"></i>                                                </button>
                                            </a>
                                        @else
                                            <button
                                                class="text-2xl md:w-16 md:h-16 w-12 h-12 rounded-full bg-red-400 hover:bg-red-600 text-white"
                                                disabled>
                                                <i class="fa-solid fa-eye-slash"></i>
                                            </button>
                                        @endif
                                    @else
                                        <div>
                                            ?? - ??
                                        </div>
                                        <div></div>
                                    @endif
                                </div>
                            @endforeach
                        @empty
                            <div class="text-center text-lg font-semibold italic pt-5">
                                Hurrá! Nincsen kitöltendő tesztem!
                            </div>
                        @endforelse
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
