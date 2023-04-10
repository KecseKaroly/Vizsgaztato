<?php

namespace App\Http\Livewire;

use App\Events\TestEnded;
use App\Actions\StoreTestAttempt;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\testAttempt;
use Illuminate\Session\Store;
use Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class ExamTaskWrite extends Component
{
    public $quizEnded = false;
    public $test;
    public $type;
    public $course;
    public $defaultTestLiveWire;

    protected $listeners = ['timeRanOut', 'saveData'=>'SaveDataToSession'];
    public function updateOptionOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->test['questions'][$indexek[0]]['options'][$indexek[1]]);
        }
        $this->test['questions'][$indexek[0]]['options'] = $newAnswers;
    }

    public function endTest() {
        if($this->type == "test")
        {
            $attempt = (new StoreTestAttempt())->store($this->test);
            event(new TestEnded($attempt));
            return redirect()->route('testAttempts.index', [$this->course->id, $attempt->test_id]);
        }
        else {
            $this->quizEnded = true;
        }
    }

    public function timeRanOut() {
        $this->endTest();
    }

    public function render()
    {
        return view('livewire.exam-task-write');
    }
    public function mount($testLiveWire, $course, $type="test")
    {
        $this->type = $type;
        if($this->type == 'quiz') {
            $this->defaultTestLiveWire = $testLiveWire;
        }
        $this->course = $course;
        $this->test = $testLiveWire;
    }

    public function SaveDataToSession() {
        if($this->type == "test")
            Session::put('attempt_'.$this->test['attempt_id'], $this->test);
    }

    public function resetQuiz() {
        foreach($this->defaultTestLiveWire['questions'] as $question) {
            if($question['type'] != 'TrueFalse')
                shuffle($question['options']);
        }
        shuffle($this->defaultTestLiveWire['questions']);
        $this->test = $this->defaultTestLiveWire;
        $this->quizEnded = false;
    }
}
