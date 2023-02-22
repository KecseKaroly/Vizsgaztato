<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TestsGroupsController;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Alert;

class ExamTaskEdit extends Component
{
    public $deletedQuestions = [];
    public $deletedOptions = [];

    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $testResultsVisible;
    public $testId;


    public function mount($testLiveWire)
    {
        $this->testId = $testLiveWire['id'];
        $this->questions = $testLiveWire['questions'];
        $this->testTitle = $testLiveWire['title'];
        $this->testAttempts = $testLiveWire['maxAttempts'];
        $this->durationMinute = $testLiveWire['duration'];
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
    public function Add_Question($index)
    {
        array_unshift($this->questions,
            [
                'text' => '',
                'type' => '',
                'options' => [],
                'right_answer_index' => '',
            ]);
    }

    public function Remove_Question($questionIndex)
    {
        if (array_key_exists('id', $this->questions[$questionIndex])) {
            array_push($this->deletedQuestions, $this->questions[$questionIndex]);
        }
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
        if (array_key_exists('id', $this->questions[$questionIndex]['options'][$optionIndex])) {
            array_push($this->deletedOptions, $this->questions[$questionIndex]['options'][$optionIndex]);
        }
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
    }

    public function Save_Test()
    {
        $test = test::find($this->testId);
        $test->title = $this->testTitle;
        $test->maxAttempts = $this->testAttempts;
        $test->duration = $this->durationMinute;
        $test->creator_id = auth()->id();
        $test->save();
        foreach ($this->questions as $questionIndex => $question) {
            $questionModel = question::find($question['id']);
            $questionModel->text = $question['text'];
            $questionModel->type = $question['type'];
            $questionModel->test_id = $test->id;
            $questionModel->save();

            foreach ($question['options'] as $optionIndex => $option) {
                $optionModel = option::find($option['id']);
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
        foreach ($this->deletedQuestions as $question) {
            $question = question::find($question['id']);
            $question->delete();
        }
        foreach ($this->deletedOptions as $option) {
            $option = option::find($answer['id']);
            $option->delete();
        }

        Alert::success('A teszt módosítása sikeresen megtörtént!');
        return redirect()->route('test.index');
    }

    public function render()
    {
        return view('livewire.exam-task-edit');
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
