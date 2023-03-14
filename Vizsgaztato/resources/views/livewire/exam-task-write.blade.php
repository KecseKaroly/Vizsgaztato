<div class="pt-4 mb-24 select-none" wire:poll.5s="SaveDataToSession">
    <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
        @if( $type == "test" )
            <a href="{{route('test.index', $course[0] )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        @else
            <a href="{{route('quizzes.index', $course[0] )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        @endif
    </div>
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-6 font-black text-3xl">{{ $type == "test" ? "Vizsga feladatsor kitöltése" : "Kvíz kitöltése" }}</p>
        <div class="md:flex ">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg border  shadow-xl">
                <div
                    class="lg:px-12 lg:py-12 lg:my-12 md:px-9 md:py-9 md:my-9 sm:px-6 sm:py-6 sm:my-6  px-3 py-3 my-3 max-w w-full bg-white rounded-lg  shadow-lg ">
                    @foreach($test['questions'] as $questionIndex => $question)
                        <div class="lg:px-12 md:px-8 px-4  py-6">
                            <div class="flex  sm:flex-row flex-col justify-between">
                                <div>
                                    <p class="text-lg font-bold break-words">{{$question['text']}}</p>
                                </div>
                                <div></div>
                                <div>
                                    <p class="font-medium break-words">{{$question['maxScore']}} pont</p>
                                </div>
                            </div>
                            @switch($question['type'])
                                @case('TrueFalse')
                                    <div class="flex flex-col md:flex-row">
                                        @break
                                        @case('Sequence')
                                            <div wire:sortable="updateOptionOrder">
                                                @break
                                                @default
                                                    <div class="flex flex-col">
                                                        @break
                                                        @endswitch

                                                        @foreach($question['options'] as $optionIndex => $option)
                                                            @switch($question['type'])
                                                                @case('TrueFalse')
                                                                    <div
                                                                        @class([
                                                                            "border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center w-full rounded   bg-slate-100 hover:bg-slate-300",
                                                                           "border-green-500" => $quizEnded && $question['actual_ans'] == $optionIndex && $option['expected_ans'] == "checked",
                                                                           "border-green-500 border-dashed" => $quizEnded && $question['actual_ans'] != $optionIndex && $option['expected_ans'] == "checked",
                                                                           "border-red-500" => $quizEnded && $question['actual_ans'] == $optionIndex && $option['expected_ans'] == "unchecked"
                                                                        ])
                                                                        id="option_div_{{$option['id']}}"
                                                                    >
                                                                        <input
                                                                            wire:model="test.questions.{{$questionIndex}}.actual_ans"
                                                                            value="{{$optionIndex}}"
                                                                            id="option_{{$option['id']}}"
                                                                            type="radio"
                                                                            name="question_{{$question['id']}}"
                                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 ">
                                                                        <label for="option_{{$option['id']}}"
                                                                               class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">{{$option['text']}}</label>
                                                                    </div>
                                                                    @break
                                                                @case('OneChoice')
                                                                    <div
                                                                        @class([
                                                                                "border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded bg-slate-100 hover:bg-slate-300",
                                                                                "border-green-500" => $quizEnded && $question['actual_ans'] == $optionIndex && $option['expected_ans'] == "checked",
                                                                                "border-green-500 border-dashed" => $quizEnded && $question['actual_ans'] != $optionIndex && $option['expected_ans'] == "checked",
                                                                                "border-red-500" => $quizEnded && $question['actual_ans'] == $optionIndex && $option['expected_ans'] == "unchecked"
                                                                                ])
                                                                        id="option_{{$option['id']}}">
                                                                        <input
                                                                            wire:model="test.questions.{{$questionIndex}}.actual_ans"
                                                                            value="{{$optionIndex}}"
                                                                            id="option_{{$question['id']}}_{{$option['id']}}"
                                                                            type="radio"
                                                                            name="question_{{$question['id']}}"
                                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                                                                        <label
                                                                            for="option_{{$question['id']}}_{{$option['id']}}"
                                                                            class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">
                                                                            {{$option['text']}}</label>
                                                                    </div>
                                                                    @break
                                                                @case('MultipleChoice')
                                                                    <div
                                                                        @class([
                                                                                    "border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 lg:ml-2  ml-0 mx-0 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300",
                                                                                    "border-green-500" => $type == 'quiz' && $quizEnded && $option['actual_ans'] && $option['expected_ans'] == "checked",
                                                                                    "border-green-500 border-dashed" => $type == 'quiz' && $quizEnded && !$option['actual_ans'] && $option['expected_ans'] == "checked" ,
                                                                                    "border-red-500" => $type == 'quiz' && $quizEnded && $option['actual_ans'] && $option['expected_ans'] == "unchecked",
                                                                                ])
                                                                        id="option_div_{{$option['id']}}">
                                                                        <input
                                                                            wire:model="test.questions.{{$questionIndex}}.options.{{$optionIndex}}.actual_ans"
                                                                            id="option_{{$option['id']}}"
                                                                            type="checkbox"
                                                                            name="option_{{$option['id']}}"
                                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                        <label for="option_{{$option['id']}}"
                                                                               class="break-words font-sans  pl-2 w-full text-md italic font-normal text-gray-900">{{$option['text']}}</label>
                                                                    </div>
                                                                    @break
                                                                @case('Sequence')
                                                                    <div
                                                                        @class([
                                                                                " border-4 lg:mx-16 lg:px-8 lg:my-4 lg:py-2 mx-2 my-1 py-1 pl-6 flex items-center rounded border  bg-slate-100 hover:bg-slate-300",
                                                                                 "border-green-500" => $type == 'quiz' && $quizEnded && $optionIndex+1 == $option['expected_ans'],
                                                                                 "border-red-500" => $type == 'quiz' && $quizEnded && $optionIndex+1 != $option['expected_ans'],
                                                                        ])
                                                                        wire:sortable.item="{{$questionIndex}}_{{ $optionIndex }}_{{ $option['id'] }}"
                                                                        wire:key="question-{{$questionIndex}}-option--{{ $option['id'] }}"
                                                                        wire:sortable.handle>
                                                                        <h4>{{ $option['text'] }}</h4>
                                                                    </div>
                                                                    @break
                                                            @endswitch
                                                        @endforeach
                                                    </div>
                                            </div>
                                            @endforeach
                                    </div>
                                    <div class="flex flex-col items-center mb-10 w-full">
                                        <button wire:click="endTest()"
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-1/2">
                                            Befejezés
                                        </button>
                                    </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function endTest() {
        @this.emit('timeRanOut');
        }
    </script>
@endpush

