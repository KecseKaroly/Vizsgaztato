@extends('layouts.app')
@section('title', 'Kvíz módosítása')
@section('content')
    <div>
        @livewire('exam-task-edit', ['testLiveWire' => $testLiveWire, 'course'=>$course, 'type'=>'quiz'])
    </div>
@endsection
