@extends('layouts.app')
@section('content')

<div class="mt-24 mb-24 select-none">
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor eredménye</p> 
        <div class="md:flex ">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg border  shadow-xl">
                
                @foreach ($testResults as $testResultIndex => $testResult )
                    <div class="lg:px-12 lg:py-12 lg:my-12 md:px-9 md:py-9 md:my-9 sm:px-6 sm:py-6 sm:my-6  px-3 py-3 my-3 max-w w-full bg-gray-200 rounded-lg  shadow-lg ">
                        <p class="text-2xl font-black font-mono">{{$testResultIndex}} asd</p>
                        <hr class="mx-auto w-full h-1 bg-gray-900 rounded border-0 my-3">
                       
                    </div>
                @endforeach
                
            </div>
        </div>
    </div>
</div>
@endsection
