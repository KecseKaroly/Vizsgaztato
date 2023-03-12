@extends('layouts.app')
@section('title', 'Feladatlap készítése')
@section('content')
    <div>
        @livewire('exam-task-creator', ['course'=>$course])
    </div>
@endsection
