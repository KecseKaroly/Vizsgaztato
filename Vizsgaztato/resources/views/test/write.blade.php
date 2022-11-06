@extends('layouts.app')
@section('content')
@if(isset($error))
<p>{{$error}}</p>
@else
    <div>
        @livewire('exam-task-write', ['testLiveWire' => $testLiveWire])        
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endif
@endsection
