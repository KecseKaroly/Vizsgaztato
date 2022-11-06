@extends('layouts.app')
@section('content')
    <div>
        @livewire('exam-task-creator')        
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endsection
