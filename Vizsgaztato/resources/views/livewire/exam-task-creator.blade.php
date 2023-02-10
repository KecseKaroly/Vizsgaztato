<div class="pt-24 pb-24" wire:click="ResetInputField()">
    @if(0 < count($tasks) && $testTitle != "")
        <div class="absolute right-0 bottom-0">
            <button type="button" class=" flex items-center py-3 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                wire:click="Save_Test">
                <svg class="h-7 w-7 text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />  <polyline points="7 3 7 8 15 8" />
                </svg>
                <span class="pl-3 font-black">Mentés</span>
            </button>
        </div>
    @endif
    <div class="max-w-full mx-auto rounded-xl overflow-hidden  lg:w-4/6 md:w-8/12 sm:w-11/12 w-11/12">
        <p class="text-center mb-12 font-black text-3xl">Vizsga feladatsor kitöltése</p>
        <div class="flex flex-col w-full relative">
            <div class="flex flex-row justify-start">
                <div class="w-4/12">
                    <p><label for="titleOfTestAttempt">Feladatlap címe: </label></p>
                </div>
                <div class="ml-6 w-full">
                    <input type="text" id="titleOfTestAttempt"  wire:model="testTitle" class="w-full bg-orange-200 border-4 rounded-lg text-lg placeholder-[#716156]">
                </div>
            </div>
            <div class="flex flex-row justify-start">
                <div class="w-4/12">
                    <p><label for="numOfTestAttempt">Lehetséges kitöltések száma: </label></p>
                </div>
                <div class="ml-6 w-full">
                    <input type="text"  wire:model="testAttempts" id="numOfTestAttempt" class="w-full bg-orange-200 border-4 rounded-lg text-lg placeholder-[#716156]">
                </div>
            </div>
            <div id="container">
                <div class="lg:flex">
                    <div class="mb-2">
                        <div class="w-full ">
                            <input type="text" wire:model.debounce.500ms="searchValue" id="emailInputField" placeholder="Minta Csoport" autocomplete="off" class="font-semibold text-md focus:border-blue-800 focus:bg-blue-100 bg-blue-50 rounded-t-lg border-blue-300  lg:w-fit w-full" size="40"/>
                            <div class="w-full relative">
                                @if(!empty($searchValue))
                                    <div id="searchResults" wire:loading.remove class="text-md absolute z-10 top-0 -left-0.5 -right-0.5 divide-y-2">
                                        @if(!empty($searchResults))
                                            @foreach($searchResults as $index => $searchResult)
                                                <div class="px-2 py-1 font-semibold text-md hover:bg-blue-400 border-x-2 border-blue-800 bg-blue-50 w-full">
                                                    <button wire:click="addToSelectedResults({{ $index }})">{{ $searchResult['name'] }}</button>
                                                </div>
                                            @endforeach
                                        @else
                                        <div class="px-2 py-1 border-x-4 border-blue-300 hover:bg-blue-200 bg-blue-50 border-b-2">Nincs találat...</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mt-2">
                @foreach ($selectedResults as $index => $selectedResult)
                    <div class="bg-stone-50 border-2 border-stone-800 text-stone-500 rounded-full py-1 w-fit flex content-center text-sm divide-x-2">
                        <div class="mx-2"><i class="fa-solid fa-users"></i></div>
                        <div class="flex pl-1.5">
                            {{ $selectedResult['name'] }}
                            <div class="mx-1">
                                <button wire:click="removeFromSelectedResults({{ $index }})">
                                    <span class="fa-stack" style="font-size: 0.75em;">
                                        <i class="fa-solid fa-circle-xmark fa-stack-2x text-red-500"></i>
                                        <i class="fa-solid fa-xmark fa-stack-1x fa-inverse"></i>
                                      </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sm:mt-2 mt-4">
                <button wire:click="Add_Task" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    <span class=" font-black ">Új feladat hozzáadása</span>
                </button>
            </div>
        </div>

        <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lgshadow-xl flex flex-col">
                @foreach ($tasks as $taskIndex => $task)
                    <div class="lg:pl-12 lg:py-12 lg:my-12 md:pl-9 md:py-9 md:my-9 sm:pl-6 sm:py-6 sm:my-6  pl-3 pr-2 py-3 my-3 max-w w-full bg-white rounded-lg  shadow-lg">
                        <div class="flex flex-col">
                                <div class="flex sm:flex-row flex-col mb-2">
                                    <div>
                                        <select name="task-types{{$taskIndex}}" wire:model="tasks.{{$taskIndex}}.type"
                                        wire:change="$emit('taskTypeChanged', {{$taskIndex}})"
                                        class="bg-orange-300 border-gray-300 text-orange-900 text-sm rounded-lg  font-bold
                                            focus:ring-orange-100 focus:border-orange-800 block  py-3 w-fit">
                                            <option selected hidden>Válasszon feladattípust...</option>
                                            <option value="TrueFalse" class="font-bold">Igaz/Hamis</option>
                                            <option value="OneChoice" class="font-bold">1 megoldásos választás</option>
                                            <option value="MultipleChoice" class="font-bold">Több megoldásos választás</option>
                                            <option value="Sequence" class="font-bold">Sorrend</option>
                                        </select>
                                    </div>
                                    <div class="flex mt-3">
                                        @if($task['type'] != "")
                                        <button wire:click="Add_Question({{$taskIndex}})" class="flex items-center text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-2.5  text-center ml-1.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="white" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-white font-black pl-1">Kérdés</span>
                                        </button>
                                        @endif
                                        <button wire:click="Remove_Task({{$taskIndex}})"  class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3  text-center ml-1.5 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <input type="text"  wire:model="tasks.{{ $taskIndex }}.text"  placeholder="Feladat szövege"
                                    class="bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156] w-11/12">
                                </div>
                        </div>

                        @foreach($task['questions'] as $questionIndex => $question)
                            <div class="lg:px-12 md:px-8 px-4  py-6">
                                <div class="flex sm:flex-row flex-col">
                                        <div class="w-full h-full">
                                            <input type="text"  wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.text"  placeholder="{{$questionIndex+1}}. Kérdés szövege"
                                            class="w-11/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                        </div>
                                        <div class="flex sm:my-0 my-2">
                                            @if($task['type'] != "TrueFalse")
                                                <button wire:click="Add_Answer({{$taskIndex}},{{$questionIndex}})" class="flex items-center text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-2.5 py-2.5  text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.0" stroke="white" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-white font-black pl-1">Válasz</span>
                                                </button>
                                            @endif
                                            <button wire:click="Remove_Question({{$taskIndex}},{{$questionIndex}})"  class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                </div>
                                @switch($task['type'])
                                        @case('TrueFalse')
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-center">
                                            @break
                                        @case('Sequence')
                                            <div wire:sortable="updateTaskOrder">
                                            @break
                                        @default
                                            <div>
                                @endswitch
                                @foreach($question['answers'] as $answerIndex => $answer)
                                        @switch($task["type"])
                                            @case("TrueFalse")
                                                <div class="lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                                    <label>
                                                        {{$answer["text"]}}
                                                        <input type="radio" name="Answer_{{$taskIndex}}_{{$questionIndex}}" value="{{$answerIndex}}" wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.right_answer_index">
                                                    </label>
                                                </div>
                                                @break
                                            @case("OneChoice")
                                                <div class="sm:flex-row flex-col lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                                <div class="w-full">
                                                    <label>
                                                        <input type="radio" name="Answer_{{$taskIndex}}_{{$questionIndex}}" value="{{$answerIndex}}" wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.right_answer_index">Ez a helyes
                                                    </label>
                                                    <input type="text"  wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.answers.{{$answerIndex}}.text"  placeholder="{{$answerIndex}}. Válasz szövege" class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                                </div>
                                                <div class="grid place-items-center my-3">
                                                    <button wire:click="Remove_Answer({{$taskIndex}},{{$questionIndex}}, {{$answerIndex}})" class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                </div>
                                                @break
                                            @case("MultipleChoice")
                                                <div class="sm:flex-row flex-col lg:mx-16 lg:px-8 lg:my-2 lg:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                                <div class="w-full">
                                                    <label>
                                                        <input type="checkbox" name="Answer_{{$taskIndex}}_{{$questionIndex}}" value="{{$answerIndex}}" wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.answers.{{$answerIndex}}.solution">
                                                    </label>
                                                    <input type="text" wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.answers.{{$answerIndex}}.text"  placeholder="{{$answerIndex}}. Válasz szövege" class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                                </div>
                                                <div class="grid place-items-center my-3">
                                                    <button wire:click="Remove_Answer({{$taskIndex}},{{$questionIndex}}, {{$answerIndex}})" class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                </div>
                                                @break
                                            @case("Sequence")
                                                <div class="sm:flex-row flex-col lg:ml-16 lg:px-8 lg:my-2 lg:py-4 ml-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300" wire:sortable.item="{{$taskIndex}}_{{$questionIndex}}_{{ $answerIndex }}_{{ $answer['id'] }}" wire:key="task-{{ $answer['id'] }}" wire:sortable.handle>
                                                    <div class="w-full">
                                                        <input type="text" value="#{{$answerIndex}}Answer" wire:model="tasks.{{ $taskIndex }}.questions.{{$questionIndex}}.answers.{{$answerIndex}}.text"  placeholder="{{$answerIndex}}. Válasz szövege" class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                                    </div>
                                                    <div class="flex flex-row">
                                                        <button wire:click="Remove_Answer({{$taskIndex}},{{$questionIndex}}, {{$answerIndex}})" class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </div>
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
