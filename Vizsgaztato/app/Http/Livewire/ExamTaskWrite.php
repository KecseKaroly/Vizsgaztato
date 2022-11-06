<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Auth;
use App\Models\answer;
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
                            //Debugbar::log("TrueFalse: ".$answer['text'].", Expected: ".$answer['expected_ans'].", Actual:". $tempAns);
                            break;
                        case "OneChoice":
                            $tempAns = $answerIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                            //Debugbar::log("OneChoice: ".$answer['text'].", Expected: ".$answer['expected_ans'].", Actual:". $tempAns);
                            break;
                        case "MultipleChoice":
                            $tempAns = $answer['actual_ans'] == '' ? "unchecked" : "checked"; 
                            //Debugbar::log("MultipleChoice: ".$answer['text'].", Expected: ".$answer['expected_ans'].", Actual:". $tempAns);
                            break;
                        case "Sequence":
                            $tempAns = $answerIndex;
                            //Debugbar::log("Sequence: ".$answer['text'].", Expected: ".$answer['expected_ans'].", Actual:". $answerIndex);
                            break;
                    }
                    if($task['type'] == 'Sequence') {
                        if($answer['expected_ans'] == $tempAns)
                        {
                            $border_color = "border-green-500";
                            $achievedScore += $answer['score'];
                        }
                        else {
                            $border_color = "border-red-500";
                        }
                    }
                    else {
                        if($answer['expected_ans'] == "checked" && $tempAns == $answer['expected_ans'])
                        {
                            $border_color = "border-green-500";
                            $achievedScore += $answer['score'];
                        }
                    }
                    $givenAnswer = new given_answer();
                    $givenAnswer->attempt_id = $attempt->id;
                    $givenAnswer->answer_id = $answer['id'];
                    $givenAnswer->question_id = $question['id'];
                    $givenAnswer->given = $tempAns;
                    $givenAnswer->save();
                }
            }
        }
        $attempt->maxScore = $maxScore;
        $attempt->achievedScore = $achievedScore;
        $attempt->save();
        return redirect('/test/'.$this->test['id'].'/result/'.$attempt->id);
    }
}
