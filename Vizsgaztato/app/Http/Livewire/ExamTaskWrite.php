<?php

namespace App\Http\Livewire;

use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use App\Models\testAttempt;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class ExamTaskWrite extends Component
{

    public $test;

    protected $listeners = ['timeRanOut'];
    public function updateOptionOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->test['questions'][$indexek[0]]['options'][$indexek[1]]);
        }
        $this->test['questions'][$indexek[0]]['options'] = $newAnswers;
    }

    public function endTest() {
        $maxScore = 0;
        $achievedScore = 0;
        $attempt = new testAttempt();
        $attempt->user_id = auth()->id();
        $attempt->test_id = $this->test['id'];
        $attempt->maxScore = $maxScore;
        $attempt->achievedScore = $achievedScore;
        $attempt->group_id = $this->test['group_id'];
        $attempt->save();
            foreach($this->test['questions'] as $questionIndex => $question) {
                foreach($question['options'] as $optionIndex => $option) {
                    $maxScore += $option['score'];
                    switch($question['type']) {
                        case "TrueFalse":
                            $tempAns = $optionIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                            break;
                        case "OneChoice":
                            $tempAns = $optionIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                            break;
                        case "MultipleChoice":
                            $tempAns = $option['actual_ans'] == '' ? "unchecked" : "checked";
                            break;
                        case "Sequence":
                            $tempAns = $optionIndex+1;
                            break;
                    }
                    if($tempAns == $option['expected_ans'])
                    {
                        $achievedScore += $option['score'];
                    }
                    $answer = answer::where('solution', $tempAns)->first();
                    $givenAnswer = new given_answer();
                    $givenAnswer->attempt_id = $attempt->id;
                    $givenAnswer->option_id = $option['id'];
                    $givenAnswer->answer_id = $answer->id;
                    $givenAnswer->save();
                }
            }
        $attempt->maxScore = $maxScore;
        $attempt->achievedScore = $achievedScore;
        $attempt->save();
        return redirect()->route('testAttempts.show', $attempt->id);
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
}
