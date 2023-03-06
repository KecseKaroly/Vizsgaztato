<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CoursesGroupsController;
use App\Models\group;
use App\Http\Controllers\TestsGroupsController;
use Livewire\Component;
use Alert;

class SearchGroups extends Component
{
    public $searchValue;
    public $objectToAttachTo;
    public $objectType;
    public $searchResults;
    public $selectedResults;
    public $currentGroups;
    public $givenGroups;
    public $deletedGroups;

    public function updatedSearchValue()
    {
        $this->searchResults = group::where('name', 'LIKE', '%' . $this->searchValue . '%')->get()->toArray();
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
        if($this->objectType == 'test')
        {
            (new TestsGroupsController)->store($this->selectedResults, $this->deletedGroups, $this->objectToAttachTo);
        }
        else{
            (new CoursesGroupsController)->store($this->selectedResults, $this->deletedGroups, $this->objectToAttachTo);
            return redirect()->route('courses.members', $this->objectToAttachTo);
        }
        $this->dispatchBrowserEvent('groupsAdded');
    }

    public function render()
    {
        return view('livewire.search-groups');
    }

    public function mount($currentGroups, $objectToAttachTo, $objectType = 'test')
    {
        $this->givenGroups = $currentGroups->toArray();
        $this->objectToAttachTo = $objectToAttachTo;
        $this->objectType = $objectType;
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
