<?php

namespace App\Http\Livewire;

use App\Models\GroupMessage;
use App\Models\User;
use Livewire\Component;

class GroupChat extends Component
{
    public $group;

    protected $listeners = ['newMessageReceived'];

    public function mount($group) {
        $this->group = $group;
    }
    public function newMessageReceived($newMessage) {
        $this->dispatchBrowserEvent('messageAdded');
    }
    public function render()
    {
        return view('livewire.group-chat');
    }
}
