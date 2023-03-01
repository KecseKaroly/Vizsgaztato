<?php

namespace App\Http\Livewire;

use App\Actions\DeleteOptions;
use App\Actions\DeleteQuestions;
use App\Events\TestUpdated;
use App\Http\Controllers\TestsGroupsController;
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

class ExamTaskEdit extends Component
{
    public $deletedQuestions = [];
    public $deletedOptions = [];

    public $questions;
    public $testTitle;
    public $testAttempts;
    public $durationMinute;
    public $resultsViewable;
    public $testId;


    public function mount($testLiveWire)
    {
        $this->testId = $testLiveWire['id'];
        $this->questions = $testLiveWire['questions'];
        $this->testTitle = $testLiveWire['title'];
        $this->testAttempts = $testLiveWire['maxAttempts'];
        $this->durationMinute = $testLiveWire['duration'];
        $this->resultsViewable = $testLiveWire['resultsViewable'];
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
        $test->resultsViewable = (bool)($this->resultsViewable);
        $test->save();

        (new TestService())->update($test, $this->questions);
        (new DeleteQuestions())->delete($this->deletedQuestions);
        (new DeleteOptions())->delete($this->deletedOptions);
        //event(new TestUpdated($test));
        Alert::success('A teszt módosítása sikeresen megtörtént!');
        return redirect()->route('test.index');
    }

    public function render()
    {
        return view('livewire.exam-task-edit');
    }

    public function updateOptionOrder($list)
    {
        dd($list);
        $newOptions = [];
        foreach ($list as $element) {
            $indexek = explode("_", $element["value"]);
            array_push($newOptions, $this->questions[$indexek[0]]['options'][$indexek[1]]);
        }
        $this->questions[$indexek[0]]['options'] = $newOptions;
    }
}
