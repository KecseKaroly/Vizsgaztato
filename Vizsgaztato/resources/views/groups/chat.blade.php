@extends('layouts.app')
@section('title', 'Csoport üzenetei')
@section('content')
    @livewire('group-chat', ['group'=>$group])
@endsection
