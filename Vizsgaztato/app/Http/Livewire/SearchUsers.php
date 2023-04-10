<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CoursesUsersController;
use Livewire\Component;
use App\Models\User;
use App\Http\Controllers\GroupInvController;
use Barryvdh\Debugbar\Facade as Debugbar;
use Alert;

class SearchUsers extends Component
{
    public $objectToAttachTo;
    public $objectType;
    public $searchValue;
    public $searchResults;
    public $selectedResults;

    public function updatedSearchValue() {
            $this->searchResults = User::where('email', 'LIKE', '%'.$this->searchValue.'%')
                                    ->where('auth', '1')
                                    ->where('id', '!=', auth()->id())->get()->toArray();

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
        if($this->selectedResults != []) {
            if($this->objectType == 'group')
            {
                (new GroupInvController)->store($this->selectedResults, $this->objectToAttachTo);
            }
            else {
                (new CoursesUsersController)->store($this->selectedResults, $this->objectToAttachTo);
            }
            $this->dispatchBrowserEvent('inviteRequestsSent');
            $this->selectedResults = [];
        }
    }

    public function render()
    {
        return view('livewire.search-users');
    }

    public function mount($objectToAttachTo, $objectType = 'group')
    {
        $this->objectToAttachTo = $objectToAttachTo;
        $this->objectType = $objectType;
        $this->selectedResults = [];
        $this->ResetInputField();
    }

    public function ResetInputField() {

        $this->searchValue = "";
        $this->searchResults = [];
    }
}
