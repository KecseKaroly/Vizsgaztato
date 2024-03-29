<div class="mt-4 mb-24">
    <div class="md:w-1/12 md:ml-12 mb-4 mr-8">
        @if( $type == "test" )
            <a href="{{route('test.index', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        @else
            <a href="{{route('quizzes.index', $course )}}">
                <button
                    class="text-center my-2 ml-4  py-1.5 text-lg font-bold text-blue-900 bg-slate-100 rounded-md w-full">
                    Vissza
                </button>
            </a>
        @endif
    </div>
        <p class="text-center mb-12 font-black text-3xl">{{ $type == "test" ? "Vizsga feladatsor készítése" : "Kvíz készítése" }}</p>
        <button wire:click="Add_Question"
                class="fixed left-6 bottom-6 text-white text-lg bg-green-700 hover:bg-green-800 rounded-full px-5 py-2.5 text-center">
            <i class="fa-solid fa-circle-plus"></i> Új kérdés
        </button>
        @if(0 < count($questions) && $testTitle != "")
            <button type="button"
                    class="fixed right-6 bottom-6 text-white text-lg bg-green-700 hover:bg-green-800 rounded-md px-5 py-2.5 text-center"
                    wire:click="Save_Test">
                <i class="fa-regular fa-floppy-disk"></i> Mentés
            </button>
        @endif
        <div class="max-w-full mx-auto overflow-hidden lg:w-4/6 md:10-12  w-11/12">
            <div class="flex flex-col w-full relative rounded-xl bg-slate-50 border border-black py-5 px-8 mb-5">
                <div class="flex flex-row flex-wrap justify-start">
                    <div class="font-semibold text-lg">
                        <p><label for="titleOfTestAttempt">Feladatlap címe:  @error('testTitle') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror</label></p>

                    </div>
                    <div class="w-full">
                        <input type="text" id="titleOfTestAttempt" wire:model="testTitle"
                               class="w-full bg-zinc-200 border-2 rounded-lg text-lg placeholder-[#716156]">
                    </div>
                </div>
                @if($type == "test")
                <div class="flex flex-row flex-wrap justify-start mt-3">
                    <div class="font-semibold text-lg">
                        <p><label for="numOfTestAttempt">Lehetséges kitöltések száma:  @error('testAttempts') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror</label></p>
                    </div>
                    <div class="w-full">
                        <input type="text" wire:model="testAttempts" id="numOfTestAttempt"
                               class="w-full bg-zinc-200 border-2 rounded-lg text-lg placeholder-[#716156]">
                    </div>
                </div>
                <div class="flex flex-row flex-wrap justify-start mt-3">
                    <div class="font-semibold text-lg">
                        <p><label for="durationMinute">Teszt kitöltési ideje @error('durationMinute') <span
                                    class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror</label></p>
                    </div>
                    <div class="w-full flex">
                        <div>
                            <input type="number" wire:model="durationMinute" id="durationMinute"
                                   min="5" max="120"
                                   class="bg-zinc-200 border-2 rounded-lg text-lg placeholder-[#716156]" @disabled($type=="quiz")/>
                            <label for="durationMinute" class="font-semibold text-lg">perc</label>
                        </div>
                    </div>
                </div>
                @else
                    <div class="flex flex-wrap justify-between mb-3">
                        <select name="module-selection"
                                wire:model="module_id"
                                class="bg-orange-300 border-gray-300 text-orange-900 text-sm rounded-lg  font-bold
                                        focus:ring-orange-100 focus:border-orange-800 block  py-3 w-fit">
                            <option @selected($module_id == null) hidden>Válasszon modult!...</option>

                            @foreach($course->modules as $module)
                                <option value="{{$module->id}}" @selected($module_id == $module->id)>{{ $module->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex flex-row justify-start mt-3 items-center">
                    <div class="w-fit font-semibold text-lg">
                        <p><label for="canViewResult">Eredmény elérhető:</label></p>
                    </div>
                    <div class="w-fit ml-3 pt-2">
                        <label class="relative inline-flex items-center mr-5 cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" id="canViewResult"
                                   wire:model="resultsViewable" @disabled($type=="quiz")>
                            <div
                                class="w-11 h-6 bg-gray-600 rounded-full peer-focus:ring-4 peer-focus:ring-blue opacity-80 peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{$resultsViewable ? 'Igen' : 'Nem'}}</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="max-w w-full lg:px-8 md:px-6 sm:px-4 px-2 bg-slate-700 rounded-lg shadow-xl flex flex-col">
                @foreach ($questions as $questionIndex => $question)
                    <div
                        class="lg:pl-12 lg:py-3 lg:my-5 md:pl-9 md:py-9 md:my-9 sm:pl-6 sm:py-6 sm:my-6 pl-3 pr-2 py-3 my-3 max-w w-full bg-white rounded-lg  shadow-lg">
                        <div class="lg:px-12 md:px-8 px-4 py-6">

                            <div class="flex flex-wrap justify-between mb-3">
                                @error('questions.'.$questionIndex.'.type') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                                <select name="question-types{{$questionIndex}}"
                                        wire:model="questions.{{$questionIndex}}.type"
                                        wire:change="$emit('questionTypeChanged', {{$questionIndex}})"
                                        id="typeSelector_{{$questionIndex}}"
                                        class="bg-orange-300 border-gray-300 text-orange-900 text-sm rounded-lg  font-bold
                                        focus:ring-orange-100 focus:border-orange-800 block  py-3 w-fit">
                                    <option selected hidden>Válasszon feladattípust...</option>
                                    <option value="TrueFalse" class="font-bold">Igaz/Hamis</option>
                                    <option value="OneChoice" class="font-bold">Egy megoldásos</option>
                                    <option value="MultipleChoice" class="font-bold">Több megoldásos
                                    </option>
                                    <option value="Sequence" class="font-bold">Sorrend</option>
                                </select>
                                <div class="flex">
                                    @if($question['type'] != '' && $question['type'] != "TrueFalse")
                                        <button wire:click="Add_Option({{$questionIndex}})"
                                                id="addOptionToQuestion_{{$questionIndex}}"
                                                class="flex items-center text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-2.5 py-2.5  text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <i class="fa-solid fa-circle-plus"></i> Válasz
                                        </button>
                                    @endif
                                    <button wire:click="Remove_Question({{$questionIndex}})"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                    @error('questions.'.$questionIndex.'.text') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                                    @error('questions.'.$questionIndex.'.right_option_index') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                                    @error('questions.'.$questionIndex.'.options') <span class="text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                                    <input type="text"
                                           id="questionText_{{$questionIndex}}"
                                           wire:model.debounce.500ms="questions.{{$questionIndex}}.text"
                                           placeholder="{{$questionIndex+1}}. Kérdés szövege"
                                           class="w-11/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                </div>

                        </div>
                        <div
                            @class(['flex flex-col md:flex-row md:items-center md:justify-center' => $question['type']=='TrueFalse'])
                            wire:sortable='updateOptionOrder'>
                            @foreach($question['options'] as $optionIndex => $option)
                                @error('questions.'.$questionIndex.'.options.'.$optionIndex.'.text') <span class="lg:mx-16 mx-2 text-sm text-red-500 font-bold">{{ $message }}</span> @enderror
                                @switch($question["type"])
                                    @case("TrueFalse")
                                        <div
                                            class="md:mx-16 md:px-8 md:my-3 lg:py-4 mx-2 my-1 py-1 pl-6 items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                            <label>
                                                <input type="radio" name="Option_{{$questionIndex}}_{{$optionIndex}}"
                                                       value="{{$optionIndex}}"
                                                       id="option_{{$questionIndex}}_{{$optionIndex}}"
                                                       wire:model="questions.{{$questionIndex}}.right_option_index">
                                                {{$option["text"]}}
                                            </label>
                                        </div>
                                        @break
                                    @case("OneChoice")
                                        <div
                                            class="sm:flex-row flex-col md:mx-16 lg:px-8 md:my-2 md:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                            <div class="w-full">
                                                <input type="radio" name="Option_{{$questionIndex}}_{{$optionIndex}}"
                                                       value="{{$optionIndex}}"
                                                       id="option_{{$questionIndex}}_{{$optionIndex}}"
                                                       wire:model="questions.{{$questionIndex}}.right_option_index">
                                                <input type="text"
                                                       id="optionText_{{$questionIndex}}_{{$optionIndex}}"
                                                       wire:model.debounce.500ms="questions.{{$questionIndex}}.options.{{$optionIndex}}.text"
                                                       placeholder="Válasz szövege"
                                                       class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                            </div>
                                            <div class="grid place-items-center my-3">
                                                <button wire:click="Remove_Option({{$questionIndex}}, {{$optionIndex}})"
                                                        id="optionRemove_{{$questionIndex}}_{{$optionIndex}}"
                                                        class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @break
                                    @case("MultipleChoice")
                                        <div
                                            class="sm:flex-row flex-col lg:mx-16 md:px-8 md:my-2 md:py-4 mx-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300">
                                            <div class="w-full">
                                                <label>
                                                    <input type="checkbox"
                                                           id="option_{{$questionIndex}}_{{$optionIndex}}"
                                                           name="Option_{{$questionIndex}}_{{$optionIndex}}"
                                                           value="{{$optionIndex}}"
                                                           wire:model="questions.{{$questionIndex}}.options.{{$optionIndex}}.solution"/>
                                                </label>
                                                <input type="text"
                                                       id="optionText_{{$questionIndex}}_{{$optionIndex}}"
                                                       wire:model.debounce.500ms="questions.{{$questionIndex}}.options.{{$optionIndex}}.text"
                                                       placeholder="{{$optionIndex}}. Válasz szövege"
                                                       class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]"/>
                                            </div>
                                            <div class="grid place-items-center my-3">
                                                <button
                                                    wire:click="Remove_Option({{$questionIndex}}, {{$optionIndex}})"
                                                    id="optionRemove_{{$questionIndex}}_{{$optionIndex}}"
                                                    class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @break
                                    @case("Sequence")
                                        <div
                                             class="sm:flex-row flex-col md:ml-16 md:px-8 md:my-2 md:py-4 ml-2 my-1 py-1 pl-6 flex items-center rounded border border-gray-200 bg-slate-100 hover:bg-slate-300"
                                             wire:sortable.item="{{$questionIndex."_".$optionIndex}}"
                                             wire:key="option-{{$questionIndex}}_{{ $optionIndex }}"
                                             wire:sortable.handle>
                                            <div class="w-full">
                                                <input type="text"
                                                       id="optionText_{{$questionIndex}}_{{$optionIndex}}"
                                                       value="#{{$optionIndex}}Option"
                                                       wire:model.debounce.500ms="questions.{{$questionIndex}}.options.{{$optionIndex}}.text"
                                                       placeholder="Válasz szövege"
                                                       class="w-10/12 bg-orange-200 border-amber-900 border-2 rounded-lg text-lg placeholder-[#716156]">
                                            </div>
                                            <div class="flex flex-row">
                                                <button
                                                    wire:click="Remove_Option({{$questionIndex}}, {{$optionIndex}})"
                                                    class=" text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-3 py-2.5  text-center ml-1.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                    <i class="fa-solid fa-trash"></i>
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
        </div>
</div>
