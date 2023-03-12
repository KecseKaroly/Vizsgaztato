@extends('layouts.app')
@section('title', 'Vizsga feladatsor eredménye')
@section('style')
<style>
    .correct {
        border: 0.25em green solid;
    }
    .incorrect {
        border: 0.25em red solid;
    }
    .missed {
        border: 0.25em lightgreen dashed;
    }
</style>
@endsection

@section('content')
<div class="md:flex">
    <div class="md:w-1/12 md:mr-0 ml-8 mr-24">
        @if(!($attempt->test['creator_id'] == auth()->id()))
            <a href="{{ route('testAttempts.index', [$course->id, $attempt->test]) }}">
                <button class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        @endif
    </div>
    <div class="mt-5 mb-24 select-none lg:w-10/12 md:w-9/12 w-11/12 mx-auto">
        <div class="max-w-full mx-auto rounded-xl overflow-hidden w-11/12">
            <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor eredménye</p>
            <div class="md:flex ">
                <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg border  shadow-xl">
                        <div class="lg:px-12 lg:py-12 lg:my-12 md:px-9 md:py-9 md:my-9 sm:px-6 sm:py-6 sm:my-6  px-3 py-3 my-3 max-w w-full bg-gray-200 rounded-lg  shadow-lg ">
                            <hr class="mx-auto w-full h-1 bg-gray-900 rounded border-0 my-3">
                            @foreach($attempt->test->questions as $questionIndex => $question)
                                <div class="lg:px-12 md:px-8 px-4  py-6">

                                    <p class="text-lg font-bold">{{$question->text}}</p>
                                    <div class="flex flex-col">

                                    @foreach($question->options as $optionIndex => $option)
                                        @switch($question['type'])
                                             @case('TrueFalse')
                                                 <div @class(["border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center w-full rounded   bg-slate-100 hover:bg-slate-300",
                                                             "correct" => $option->given_answers[0]->result == 1,
                                                             "incorrect" => $option->given_answers[0]->result == 2,
                                                             "missed" => $option->given_answers[0]->result == 3,
                                                                ]) id="answer_div_{{$option->id}}">
                                                    <input  value="{{$optionIndex}}" id="answer_{{$option->id}}" type="radio"  name="answer_{{$question->id}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" @checked($option->given_answers[0]->answer->solution == "checked") disabled>
                                                    <label for="answer_{{$option->id}}" class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900" >{{$option['text']}}</label>
                                                </div>
                                                @break
                                            @case('OneChoice')
                                                <div @class(["border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300",
                                                             "correct" => $option->given_answers[0]->result == 1,
                                                             "incorrect" => $option->given_answers[0]->result == 2,
                                                             "missed" => $option->given_answers[0]->result == 3,
                                                                ]) id="answer_div_{{$option->id}}">
                                                    <input  id="answer_{{$option->id}}" type="radio"   name="answer_{{$question->id}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" @checked($option->given_answers[0]->answer->solution == "checked") disabled>
                                                    <label for="answer_{{$option->id}}" class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">{{$option->text}}</label>
                                                </div>
                                                @break
                                            @case('MultipleChoice')
                                                <div @class(["border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 lg:ml-2  ml-0 mx-0 my-1 py-1 pl-6 flex items-center rounded bg-slate-100 hover:bg-slate-300",
                                                             "correct" => $option->given_answers[0]->result == 1,
                                                             "incorrect" => $option->given_answers[0]->result == 2,
                                                             "missed" => $option->given_answers[0]->result == 3,
                                                             ]) id="answer_div_{{$option->id}}">
                                                    <input  id="answer_{{$option->id}}" type="checkbox"  name="answer_{{$option->id}}" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" @checked($option->given_answers[0]->answer->solution == "checked") disabled>
                                                    <label for="answer_{{$option->id}}" class="break-words font-sans  pl-2 w-full text-md italic font-normal text-gray-900">{{$option->text}}</label>
                                                </div>
                                                @break
                                            @case('Sequence')
                                                <div @class(["border-solid border-4 lg:mx-16 lg:px-8 lg:my-4 lg:py-2 mx-2 my-1 py-1 pl-6 flex items-center rounded bg-slate-100 hover:bg-slate-300",
                                                             "correct" => $option->given_answers[0]->result == 1,
                                                             "incorrect" => $option->given_answers[0]->result == 2,])>
                                                    <h4>{{$option->text}}</h4>
                                                </div>
                                                @break
                                        @endswitch
                                    @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>
        </div>



    </div>
</div>

@endsection
