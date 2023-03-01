<?php

namespace App\Http\Livewire;

use App\Events\TestEnded;
use App\Actions\StoreTestAttempt;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use App\Models\testAttempt;
use Illuminate\Session\Store;
use Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class ExamTaskWrite extends Component
{

    public $test;

    protected $listeners = ['timeRanOut', 'saveData'=>'SaveDataToSession'];
    public function updateOptionOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->test['questions'][$indexek[0]]['options'][$indexek[1]]);
        }
        $this->test['questions'][$indexek[0]]['options'] = $newAnswers;
        $this->SaveDataToSession();
    }

    public function endTest() {
        $attempt = (new StoreTestAttempt())->store($this->test);
        event(new TestEnded($attempt));
        return redirect()->route('testAttempts.index', [$attempt->test_id, $attempt->group_id]);
    }

    public function timeRanOut() {
        $this->endTest();
    }

    public function render()
    {
        return view('livewire.exam-task-write');
    }
    public function mount($testLiveWire)
    {
        $this->test = $testLiveWire;
    }

    public function SaveDataToSession() {
        Session::put('attempt_'.$this->test['attempt_id'], $this->test);
    }
}
