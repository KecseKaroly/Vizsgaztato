<?php

namespace App\Http\Livewire;

use App\Models\group;
use App\Http\Controllers\TestsGroupsController;
use Livewire\Component;

class SearchGroups extends Component
{
    public $searchValue;
    public $test;
    public $searchResults;
    public $selectedResults;
    public $currentGroups;
    public $givenGroups;
    public $deletedGroups;

    public function updatedSearchValue()
    {
        $this->searchResults = group::where('name', 'LIKE', '%' . $this->searchValue . '%')->where('creator_id', auth()->id())->get()->toArray();
    }

    public function addToSelectedResults($index)
    {
        foreach ($this->selectedResults as $selectedResult) {
            if ($selectedResult['id'] == $this->searchResults[$index]['id']) {
                $this->searchValue = '';
                return;
            }
        }
        array_push($this->selectedResults, $this->searchResults[$index]);
        $this->searchValue = '';
    }

    public function removeFromSelectedResults($index)
    {
        foreach ($this->givenGroups as $givenGroup) {
            if ($givenGroup['id'] == $this->selectedResults[$index]['id']) {
                array_push($this->deletedGroups, $this->selectedResults[$index]);
            }
        }
        unset($this->selectedResults[$index]);
    }

    public function saveSelectedResults()
    {
        $result = (new TestsGroupsController)->store($this->selectedResults, $this->deletedGroups, $this->test);
    }

    public function render()
    {
        return view('livewire.search-groups');
    }

    public function mount($currentGroups, $test)
    {
        $this->givenGroups = $currentGroups->toArray();
        $this->test = $test;
        $this->ResetFields();
    }

    public function ResetFields()
    {
        $this->searchValue = "";
        $this->searchResults = [];

        $this->deletedGroups = [];
        $this->selectedResults = [];
        $this->currentGroups = [];

        foreach ($this->givenGroups as $object) {
            array_push($this->currentGroups, (array)$object);
            array_push($this->selectedResults, (array)$object);
        }
    }

}
