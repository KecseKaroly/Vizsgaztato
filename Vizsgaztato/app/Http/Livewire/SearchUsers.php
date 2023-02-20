<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Http\Controllers\GroupInvController;
use Barryvdh\Debugbar\Facade as Debugbar;


class SearchUsers extends Component
{
    public $groupId;
    public $searchValue;
    public $searchResults;
    public $selectedResults;

    public function updatedSearchValue() {
        $this->searchResults = User::where('email', 'LIKE', '%'.$this->searchValue.'%')->where('id', '!=', auth()->id())->get()->toArray();
    }

    public function addToSelectedResults($index) {
        if(!in_array($this->searchResults[$index], $this->selectedResults)) {
            $this->searchValue = '';
            array_push($this->selectedResults, $this->searchResults[$index]);
        }
    }

    public function removeFromSelectedResults($index) {
        unset($this->selectedResults[$index]);
    }

    public function saveSelectedResults() {
        $result = (new GroupInvController)->store($this->selectedResults, $this->groupId);
        $this->selectedResults = [];
    }

    public function render()
    {
        return view('livewire.search-users');
    }

    public function mount($groupId)
    {
        $this->groupId = $groupId;
        $this->selectedResults = [];
        $this->ResetInputField();
    }

    public function ResetInputField() {

        $this->searchValue = "";
        $this->searchResults = [];
    }
}
