@extends('layouts.app')
@section('title', 'Kvíz')
@section('content')
    @if(isset($error))
        <div class="mt-24 mb-24 select-none">
            <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
                <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor kitöltése</p>
                <div class="md:flex ">
                    <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-100 rounded-lg border  shadow-xl">

                        <div class="text-red-500 text-center m-6">
                            {{ $error }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div>
            @livewire('exam-task-write', ['testLiveWire' => $testLiveWire, 'course'=>$course, 'type'=>'quiz'])
        </div>

    @endif
@endsection

