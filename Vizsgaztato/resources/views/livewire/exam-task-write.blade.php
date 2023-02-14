<div class="pt-24 mb-24 select-none">
    <div class="fixed p-3 bg-slate-800 bottom-2 right-2 border rounded-lg text-stone-100 text-xl">
        <div class="flex text-center items-center">
            <i class="fa-solid fa-clock mr-2"></i>
            <div id="remainingHours">--:</div>
            <div id="remainingMinutes">--:</div>
            <div id="remainingSeconds">--</div>
        </div>
    </div>
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor kitöltése</p>
        <div class="md:flex ">
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg border  shadow-xl">
                @foreach ($test['tasks'] as $taskIndex => $task )
                    <div
                        class="lg:px-12 lg:py-12 lg:my-12 md:px-9 md:py-9 md:my-9 sm:px-6 sm:py-6 sm:my-6  px-3 py-3 my-3 max-w w-full bg-white rounded-lg  shadow-lg ">
                        <p class="text-2xl font-black font-mono">{{$task['text']}}</p>
                        <hr class="mx-auto w-full h-1 bg-gray-700 rounded border-0 my-3">
                        @foreach($task['questions'] as $questionIndex => $question)
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
                                                                        <div
                                                                            class="border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center w-full rounded   bg-slate-100 hover:bg-slate-300"
                                                                            id="answer_div_{{$answer['id']}}">
                                                                            <input
                                                                                wire:model="test.tasks.{{$taskIndex}}.questions.{{$questionIndex}}.actual_ans"
                                                                                value="{{$answerIndex}}"
                                                                                id="answer_{{$answer['id']}}"
                                                                                type="radio"
                                                                                onchange='handleChange(this);'
                                                                                name="answer_{{$question['id']}}"
                                                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 ">
                                                                            <label for="answer_{{$answer['id']}}"
                                                                                   class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">{{$answer['text']}}</label>
                                                                        </div>
                                                                        @break
                                                                    @case('OneChoice')
                                                                        <div
                                                                            class="border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300"
                                                                            id="answer_div_{{$answer['id']}}">
                                                                            <input
                                                                                wire:model="test.tasks.{{$taskIndex}}.questions.{{$questionIndex}}.actual_ans"
                                                                                value="{{$answerIndex}}"
                                                                                id="answer_{{$answer['id']}}"
                                                                                type="radio"
                                                                                onchange='handleChange(this);'
                                                                                name="answer_{{$question['id']}}"
                                                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                                                                            <label for="answer_{{$answer['id']}}"
                                                                                   class="break-words font-sans pl-2 w-full text-md italic font-normal text-gray-900">{{$answer['text']}}</label>
                                                                        </div>
                                                                        @break
                                                                    @case('MultipleChoice')
                                                                        <div
                                                                            class="border-solid border-4 lg:mx-16 lg:px-8 lg:my-2 lg:py-4 lg:ml-2  ml-0 mx-0 my-1 py-1 pl-6 flex items-center rounded   bg-slate-100 hover:bg-slate-300"
                                                                            id="answer_div_{{$answer['id']}}">
                                                                            <input
                                                                                wire:model="test.tasks.{{$taskIndex}}.questions.{{$questionIndex}}.answers.{{$answerIndex}}.actual_ans"
                                                                                id="answer_{{$answer['id']}}"
                                                                                type="checkbox"
                                                                                onchange='handleChange(this);'
                                                                                name="answer_{{$question['id']}}"
                                                                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                            <label for="answer_{{$answer['id']}}"
                                                                                   class="break-words font-sans  pl-2 w-full text-md italic font-normal text-gray-900">{{$answer['text']}}</label>
                                                                        </div>
                                                                        @break
                                                                    @case('Sequence')
                                                                        <div
                                                                            class="lg:mx-16 lg:px-8 lg:my-4 lg:py-2 mx-2 my-1 py-1 pl-6 flex items-center rounded border  bg-slate-100 hover:bg-slate-300"
                                                                            wire:sortable.item="{{$taskIndex}}_{{$questionIndex}}_{{ $answerIndex }}_{{ $answer['id'] }}"
                                                                            wire:key="task-{{ $answer['id'] }}"
                                                                            wire:sortable.handle>
                                                                            <h4>{{ $answer['text'] }}</h4>
                                                                        </div>
                                                                        @break
                                                                @endswitch
                                                            @endforeach
                                                        </div>
                                                </div>
                                                @endforeach
                                        </div>
                                        @endforeach
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

        @push('scripts')
            <script>
                const countDownClock = (minutes) => {
                    const remainingHours = document.getElementById('remainingHours');
                    const remainingMinutes = document.getElementById('remainingMinutes');
                    const remainingSeconds = document.getElementById('remainingSeconds');
                    let countdown;
                    return timer(minutes * 60);


                    function timer(seconds) {
                        const now = Date.now();             // current time in milliseconds
                        const then = now + seconds * 1000;  // current time + test duration in milliseconds
                        countdown = setInterval(() => {
                            const secondsLeft = Math.round((then - Date.now()) / 1000);   // the difference between the endtime of the test and now in seconds
                            if (secondsLeft <= 0) {
                                clearInterval(countdown);
                                remainingSeconds.textContent = `0`;
                            @this.emit('timeRanOut');
                                console.log("emitelniKéneMertVége");
                                return;
                            }
                            displayTimeLeft(secondsLeft);
                            console.log(secondsLeft);
                        }, 1000);
                    }

                    function displayTimeLeft(secondsLeft) {
                        remainingHours.textContent = `${("0" + Math.floor((secondsLeft / 3600))).slice(-2)}:`;
                        remainingMinutes.textContent = `${("0" + Math.floor((secondsLeft % 3600) / 60)).slice(-2)}:`;
                        remainingSeconds.textContent = `${("0" + secondsLeft % 60).slice(-2)}`;
                    }
                }
                countDownClock(@json($test['duration']));
            </script>
@endpush
