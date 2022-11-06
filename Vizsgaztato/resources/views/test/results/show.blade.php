@extends('layouts.app')
@section('content')

<div class="mt-24 mb-24 select-none">
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor eredménye</p> 
        <div class="md:flex ">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg border  shadow-xl">
                @foreach ($test['tasks'] as $taskIndex => $task )
                    <div class="lg:px-12 lg:py-12 lg:my-12 md:px-9 md:py-9 md:my-9 sm:px-6 sm:py-6 sm:my-6  px-3 py-3 my-3 max-w w-full bg-gray-200 rounded-lg  shadow-lg ">
                        <p class="text-2xl font-black font-mono">{{$task['text']}}</p>
                        <hr class="mx-auto w-full h-1 bg-gray-900 rounded border-0 my-3">
                        @foreach($task['questions'] as $questionIndex => $question)
                            <div class="lg:px-12 md:px-8 px-4  py-6">
                                <div class="flow-root flex sm:flex-row flex-col">
                                    <p class="float-left text-lg font-bold">{{$question['text']}}</p>
                                    <p class="float-right font-medium">{{$question['achievedScore']}}/{{$question['maxScore']}} pont</p>
                                </div>
                                @switch($task['type'])
                                    @case('TrueFalse')
                                        <div class="flex flex-col md:flex-row">                                
                                        @break
                                    
                                    @case('Sequence')  
                                        <div wire:sortable="updateTaskOrder">
                                        @break
                                    @default
                                        <div class="flex flex-col">
                                        @break
                                @endswitch
    
                                @foreach($question['answers'] as $answerIndex => $answer)
                                    @switch($task['type'])
                                         @case('TrueFalse')   
                                             <div class="{{$answer['border_color']}} border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center w-full rounded   bg-slate-100 hover:bg-slate-300" id="answer_div_{{$answer['id']}}">
                                                <input  value="{{$answerIndex}}" id="answer_{{$answer['id']}}" type="radio"  name="answer_{{$question['id']}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" {{$answer['given']=="checked" ? "checked" : ""}} disabled>
                                                <label for="answer_{{$answer['id']}}" class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900" >{{$answer['text']}}</label>
                                            </div> 
                                            @break
                                        @case('OneChoice')                                
                                            <div class="{{$answer['border_color']}} border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300" id="answer_div_{{$answer['id']}}">
                                                <input  id="answer_{{$answer['id']}}" type="radio"   name="answer_{{$question['id']}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" {{$answer['given']=="checked" ? "checked" : ""}} disabled>
                                                <label for="answer_{{$answer['id']}}" class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">{{$answer['text']}}</label>   
                                            </div> 
                                            @break
                                        @case('MultipleChoice')                                
                                            <div class="{{$answer['border_color']}} border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 lg:ml-2  ml-0 mx-0 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300" id="answer_div_{{$answer['id']}}">
                                                <input  id="answer_{{$answer['id']}}" type="checkbox"  name="answer_{{$question['id']}}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{$answer['given']=="checked" ? "checked" : ""}} disabled>
                                                <label for="answer_{{$answer['id']}}" class="break-words font-sans  pl-2 w-full text-md italic font-normal text-gray-900">{{$answer['text']}}</label>
                                            </div>
                                            @break
                                        @case('Sequence')                               
                                            <div class="{{$answer['border_color']}} border-solid border-4 lg:mx-16 lg:px-8 lg:my-4 lg:py-2 mx-2 my-1 py-1 pl-6 flex items-center rounded bg-slate-100 hover:bg-slate-300">
                                                <h4>{{$answer['text']}}</h4>
                                            </div>
                                            @break
                                    @endswitch
                                @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
