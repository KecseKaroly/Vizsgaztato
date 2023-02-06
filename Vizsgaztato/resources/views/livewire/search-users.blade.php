<div>
    <div id="container">
        <input type="text" wire:model.debounce.500ms="searchValue" id="emailInputField" autocomplete="off"/>
        <div wire:loading>Töltés...</div>
        @if(!empty($searchValue))
            <div id="searchResults">
                @if(!empty($searchResults))
                        @foreach($searchResults as $index => $searchResult)
                            <button wire:click="addToSelectedResults({{ $index }})" class="hover:bg-blue-300">{{ $searchResult['email'] }}</button>
                        @endforeach
                @else
                    <div>Nincs találat...</div>
                @endif
            </div>
        @endif
    </div>

    @foreach ($selectedResults as $index => $selectedResult)
        <div>
            {{ $selectedResult['email'] }}
            <button wire:click="removeFromSelectedResults({{ $index }})"> (x)</button>
        </div>
    @endforeach
    <div>
        <button wire:click="saveSelectedResults()">Meghívás</button>
    </div>
    <script>
        //https://stackoverflow.com/questions/15369572/jquery-focusout-for-entire-div-and-children
        element = document.getElementById("container");
        element.addEventListener('focusout', function(event) {
            if (element.contains(event.relatedTarget)) {
                return;
            }
            document.getElementById('searchResults').style.display='none';
        });
        element.addEventListener('focusin', function(event) {
            if (element.contains(event.relatedTarget)) {
                return;
            }
            document.getElementById('searchResults').style.display='block';
        });
    </script>


</div>
