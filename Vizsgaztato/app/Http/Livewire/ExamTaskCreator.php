<?php

namespace App\Http\Livewire;

use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Alert;

class ExamTaskCreator extends Component
{
    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $testResultsVisible;


    public function mount()
    {
        $this->questions = [];
        $this->testTitle = '';
        $this->testAttempts = 1;
        $this->durationMinute = 10;
        $this->testResultsVisible = true;
    }

    protected $listeners = ['questionTypeChanged'];

    public function questionTypeChanged($questionIndex)
    {
        if ($this->questions[$questionIndex]['type'] == 'TrueFalse')
        {
            $this->questions[$questionIndex]['options'] = [
                [
                    'text' => 'Igaz',
                    'solution' => '',
                    'score' => 0
                ],
                [
                    'text' => 'Hamis',
                    'solution' => '',
                    'score' => 0
                ]
            ];
        }
        else if($this->questions[$questionIndex]['type'] == 'Sequence')
        {
            $this->questions[$questionIndex]['options'] = [];
        }
    }
    public function Add_Question()
    {
        array_unshift($this->questions,
            [
                'text' => '',
                'type' => 'Sequence',
                'options' => [],
                'right_answer_index' => '',
            ]);
    }

    public function Remove_Question($questionIndex)
    {
        unset($this->questions[$questionIndex]);
    }

    public function Add_Option($questionIndex)
    {
        $solution = '';
        $score = 0;
        if ($this->questions[$questionIndex]["type"] == "Sequence") {
            $solution = count($this->questions[$questionIndex]['options']) + 1;
            $score = 1;
        }
        array_unshift($this->questions[$questionIndex]['options'], [
            'id' => ($questionIndex + 1) + count($this->questions[$questionIndex]['options']),
            'text' => '',
            'solution' => $solution,
            'score' => $score
        ]);
    }

    public function Remove_Option($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
    }

    public function Save_Test()
    {
        $test = new test;
        $test->title = $this->testTitle;
        $test->maxAttempts = $this->testAttempts;
        $test->duration = $this->durationMinute;
        $test->creator_id = auth()->id();
        $test->save();
        foreach ($this->questions as $questionIndex => $question) {
            $questionModel = new question;
            $questionModel->text = $question['text'];
            $questionModel->type = $question['type'];
            $questionModel->test_id = $test->id;
            $questionModel->save();

            foreach ($question['options'] as $optionIndex => $option) {
                $optionModel = new option;
                $optionModel->question_id = $questionModel['id'];
                $optionModel->text = $option['text'];
                $answer = null;

                switch ($questionModel->type) {
                    case 'TrueFalse':
                    case 'OneChoice':
                        if ($optionIndex == $question['right_option_index']) {
                            $answer = answer::firstOrCreate(['solution'=>'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution'=>'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'MultipleChoice':
                        if ($option['solution'] >= 0) {
                            $answer = answer::firstOrCreate(['solution'=>'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution'=>'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'Sequence':
                        $answer = answer::firstOrCreate(['solution'=>$optionIndex + 1]);
                        break;
                }
                $optionModel->expected_answer_id = $answer->id;
                $optionModel->score = $option['score'];
                $optionModel->save();
            }
        }

        Alert::success('A teszt mentése sikeresen megtörtént!');
        return redirect()->route('test.index');
    }

    public function render()
    {
        return view('livewire.exam-task-creator');
    }

    public function updateOptionOrder($list)
    {
        $newOptions = [];
        foreach ($list as $element) {
            $indexek = explode("_", $element["value"]);
            array_push($newOptions, $this->questions[$indexek[0]]['options'][$indexek[1]]);
        }
        $this->questions[$indexek[0]]['options'] = $newOptions;
    }
}
