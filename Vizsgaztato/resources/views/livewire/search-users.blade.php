<div>
    <div id="container">
        <div class="lg:flex">
            <div class="mb-2">
                <div class="w-full ">
                    <input type="text" wire:model.debounce.500ms="searchValue" id="emailInputField"
                           placeholder="mintamarton@email.com" autocomplete="off"
                           class="font-semibold text-md focus:border-blue-800 focus:bg-blue-100 bg-blue-50 rounded-t-lg border-blue-300  lg:w-fit w-full"
                           size="40"/>
                    <div class="w-full relative">
                        @if(!empty($searchValue))
                            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="ResetInputField()"></div>

                            <div id="searchResults" wire:loading.remove
                                 class="text-md absolute z-10 top-0 left-0 right-0 divide-y-2">
                                @if(!empty($searchResults))
                                    @foreach($searchResults as $index => $searchResult)
                                        <button
                                            class="text-left px-2 py-1 font-semibold text-md hover:bg-blue-400 border-x-2 border-blue-800 bg-blue-50 w-full"
                                            wire:click="addToSelectedResults({{ $index }})">{{ $searchResult['email'] }}</button>
                                    @endforeach
                                @else
                                    <div
                                        class="px-2 py-1 border-x-4 border-blue-300 hover:bg-blue-200 bg-blue-50 border-b-2">
                                        Nincs találat...
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <div>
                <button wire:click="saveSelectedResults()"
                        class="hover:bg-green-700 bg-green-500 border-2 border-green-900  text-white font-bold p-2 lg:ml-5">
                    <i class="fa-solid fa-user-plus"></i> Meghívás
                </button>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap mt-2">
        @foreach ($selectedResults as $index => $selectedResult)
            <div
                class="bg-stone-50 border-2 border-stone-800 text-stone-500 rounded-full py-1 w-fit flex content-center text-sm divide-x-2">
                <div class="mx-2"><i class="fa-solid fa-at"></i></div>
                <div class="flex pl-1.5">
                    {{ $selectedResult['email'] }}
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
</div>
