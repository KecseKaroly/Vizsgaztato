@extends('layouts.app')
@section('title', 'Kvíz')
@section('content')
    <div>
        @livewire('exam-task-write', ['testLiveWire' => $testLiveWire, 'course'=>$course, 'type'=>'quiz'])
    </div>
@endsection

