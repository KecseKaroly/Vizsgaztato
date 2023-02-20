<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Auth;
use App\Models\answer;
use App\Models\answer_value;
use App\Models\given_answer;
use App\Models\testAttempt;
use Barryvdh\Debugbar\Facade as Debugbar;
use Livewire\Component;

class ExamTaskWrite extends Component
{
    public function render()
    {
        return view('livewire.exam-task-write');
    }
    public $test;
    public function mount($testLiveWire)
    {
       $this->test = $testLiveWire;
    }
    protected $listeners = ['timeRanOut'];
    public function updateTaskOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->test['tasks'][$indexek[0]]['questions'][$indexek[1]]['answers'][$indexek[2]]);
        }
        $this->test['tasks'][$indexek[0]]['questions'][$indexek[1]]['answers'] = $newAnswers;
    }

    public function endTest() {
        $maxScore = 0;
        $achievedScore = 0;
        $attempt = new testAttempt();
        $attempt->user_id = Auth::id();
        $attempt->test_id = $this->test['id'];
        $attempt->maxScore = $maxScore;
        $attempt->achievedScore = $achievedScore;
        $attempt->save();
        foreach($this->test['tasks'] as $taskIndex => $task) {
            foreach($task['questions'] as $questionIndex => $question) {
                foreach($question['answers'] as $answerIndex => $answer) {
                    $maxScore += $answer['score'];
                    switch($task['type']) {
                        case "TrueFalse":
                            $tempAns = $answerIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                            break;
                        case "OneChoice":
                            $tempAns = $answerIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                            break;
                        case "MultipleChoice":
                            $tempAns = $answer['actual_ans'] == '' ? "unchecked" : "checked";
                            break;
                        case "Sequence":
                            $tempAns = $answerIndex+1;
                            break;
                    }
                    if($tempAns == $answer['expected_ans'])
                    {
                        $achievedScore += $answer['score'];
                    }
                    $answer_value = answer_value::where('text', $tempAns)->first();
                    $givenAnswer = new given_answer();
                    $givenAnswer->attempt_id = $attempt->id;
                    $givenAnswer->answer_id = $answer['id'];
                    $givenAnswer->question_id = $question['id'];
                    $givenAnswer->given_id = $answer_value->id;
                    $givenAnswer->save();
                }
            }
        }
        $attempt->maxScore = $maxScore;
        $attempt->achievedScore = $achievedScore;
        $attempt->save();
        return redirect()->route('checkAttemptResult', [$this->test['id'], $attempt->id]);
    }

    public function timeRanOut() {
        $this->endTest();
    }
}
