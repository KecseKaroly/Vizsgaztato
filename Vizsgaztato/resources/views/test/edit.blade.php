@extends('layouts.app')
@section('title', 'Feladatlap szerkesztése')
@section('content')
    <div>
        @livewire('exam-task-edit', ['testLiveWire' => $testLiveWire, 'groups'=>$groups])
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endsection
