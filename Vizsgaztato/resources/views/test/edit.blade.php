@extends('layouts.app')
@section('title', 'Feladatlap szerkesztése')
@section('content')
    <div>
        @livewire('exam-task-edit', ['testLiveWire' => $testLiveWire])
    </div>
@endsection
