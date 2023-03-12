@extends('layouts.app')
@section('title', 'Kvíz készítése')
@section('content')
    <div>
        @livewire('exam-task-creator', ['course' => $course, 'module'=> $module, 'type' => 'quiz'])
    </div>
@endsection
