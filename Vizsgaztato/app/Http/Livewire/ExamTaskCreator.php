<?php

namespace App\Http\Livewire;

use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use App\Models\group;
use App\Services\TestService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Alert;

class ExamTaskCreator extends Component
{
    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $resultsViewable;


    public function mount()
    {
        $this->questions = [];
        $this->testTitle = '';
        $this->testAttempts = 1;
        $this->durationMinute = 10;
        $this->resultsViewable = true;
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
                'type' => '',
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
        $test->resultsViewable = $this->resultsViewable;
        $test->creator_id = auth()->id();
        $test->save();
        (new TestService())->store($test, $this->questions);

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
