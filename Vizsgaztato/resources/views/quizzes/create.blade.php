@extends('layouts.app')
@section('title', 'Kvíz készítése')
@section('content')
    <div>
        @livewire('exam-task-creator', ['course' => $course, 'module_id'=> $module->id, 'type' => 'quiz'])
    </div>
@endsection
