<div wire:sortable="updateOptionOrder">
    @foreach ($question['options'] as $optionIndex => $option)
        <div class="..." 
          wire:sortable.item="{{$questionIndex.'_'.$optionIndex}}" 
          wire:sortable.handle>
        {{ $option['text'] }}
      </div>
    @endforeach
</div>