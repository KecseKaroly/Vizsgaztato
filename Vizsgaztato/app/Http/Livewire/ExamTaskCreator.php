<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TestsGroupsController;
use App\Models\test;
use App\Models\task;
use App\Models\question;
use App\Models\group;
use App\Models\answer;
use App\Models\answer_value;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Debugbar\Facade as Debugbar;

class ExamTaskCreator extends Component
{
    public $tasks;
    public $testTitle;
    public $testAttempts;
    public $durationHour;
    public $durationMinute;

    public $searchValue;
    public $searchResults;
    public $selectedResults;
    public function updatedSearchValue() {
        $this->searchResults = group::where('name', 'LIKE', '%'.$this->searchValue.'%')->get()->toArray();
    }

    public function addToSelectedResults($index) {
        if(!in_array($this->searchResults[$index], $this->selectedResults)){
            $this->searchValue = '';
            array_push($this->selectedResults, $this->searchResults[$index]);
        }
    }

    public function removeFromSelectedResults($index) {
        unset($this->selectedResults[$index]);
    }

    public function saveSelectedResults($testId) {
        $result = (new TestsGroupsController)->store($this->selectedResults, $testId);
    }

    public function mount() {
        $this->tasks = [];
        $this->selectedResults = [];
        $this->testTitle = "";
        $this->testAttempts = 1;
        $this->durationHour = 0;
        $this->durationMinute = 1;
        $this->ResetInputField();
    }

    public function ResetInputField() {

        $this->searchValue = "";
        $this->searchResults = [];
    }

    protected $listeners = ['taskTypeChanged'];
    public function taskTypeChanged($taskIndex) {
        if($this->tasks[$taskIndex]['type'] == 'TrueFalse' || $this->tasks[$taskIndex]['type'] == 'Sequence')
            $this->tasks[$taskIndex]['questions'] = [];
    }

    public function Add_Task()
    {
        array_unshift($this->tasks,
            [
                'text' => '',
                'type' => '',
                'questions' => [],
            ]);
    }
    public function Remove_Task($taskIndex) {
        unset($this->tasks[$taskIndex]);
    }
    public function Add_Question($index)
    {
        $answers = [];
        if($this->tasks[$index]["type"] == "TrueFalse")
        {
            $answers = [
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
        array_unshift($this->tasks[$index]['questions'],
                [
                   'text' => '',
                   'answers' => $answers,
                   'right_answer_index' => '',
                ]
            );

    }
    public function Remove_Question($taskIndex, $questionIndex) {
        unset($this->tasks[$taskIndex]['questions'][$questionIndex]);
    }

    public function Add_Answer($taskIndex, $questionIndex)
    {
        $solution = '';
        $score = 0;
        if($this->tasks[$taskIndex]["type"] == "Sequence")
        {
            $solution = count($this->tasks[$taskIndex]['questions'][$questionIndex]['answers']) + 1;
            $score = 1;
        }

        array_push($this->tasks[$taskIndex]['questions'][$questionIndex]['answers'],
         [
            'id' => ($taskIndex+1)+($questionIndex+1)+count($this->tasks[$taskIndex]['questions'][$questionIndex]['answers']),
            'text' => '',
            'solution' => $solution,
            'score' => $score
         ]
        );
    }
    public function Remove_Answer($taskIndex, $questionIndex, $answerIndex) {
        unset($this->tasks[$taskIndex]['questions'][$questionIndex]['answers'][$answerIndex]);

    }
    public function Save_Test()
    {
        $testModel = new test;
        $testModel->title = $this->testTitle;
        $testModel->maxAttempts = $this->testAttempts;
        $testModel->duration = $this->durationHour*60 + $this->durationMinute;
        $testModel->creator_id = Auth::id();
        $testModel->save();
        foreach($this->tasks as $taskIndex => $task) {
            $taskModel = new task;
            $taskModel->test_id = $testModel->id;
            $taskModel->text = $task['text'];
            $taskModel->type = $task['type'];
            $taskModel->save();

            foreach($task['questions'] as $questionIndex => $question) {
                $questionModel = new question;
                $questionModel->task_id = $taskModel['id'];
                $questionModel->text = $question['text'];
                $questionModel->save();

                foreach($question['answers'] as $answerIndex => $answer) {
                    $answerModel = new answer;
                    $answerModel->question_id = $questionModel['id'];
                    $answerModel->text = $answer['text'];
                    switch($taskModel->type) {
                        case 'TrueFalse':
                            if($answerIndex == $question['right_answer_index'])
                            {
                                $answer_value = answer_value::where('text', 'checked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "checked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 1;
                            }
                            else {
                                $answer_value = answer_value::where('text', 'unchecked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "unchecked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 0;
                            }
                            break;
                        case 'OneChoice':
                            if($answerIndex == $question['right_answer_index'])
                            {
                                $answer_value = answer_value::where('text', 'checked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "checked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 1;
                            }
                            else {
                                $answer_value = answer_value::where('text', 'unchecked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "unchecked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 0;
                            }
                            break;
                        case 'MultipleChoice':
                            if($answer['solution'] >= 0)
                            {
                                $answer_value = answer_value::where('text', 'checked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "checked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 1;
                            }
                            else {
                                $answer_value = answer_value::where('text', 'unchecked')->first();
                                if($answer_value === null)
                                {
                                    $answer_value = new answer_value();
                                    $answer_value->text = "unchecked";
                                    $answer_value->save();
                                }
                                $answer['score'] = 0;
                            }
                            break;
                        case 'Sequence':
                            $answer_value = answer_value::where('text', $answerIndex+1)->first();
                            if($answer_value === null)
                            {
                                $answer_value = new answer_value();
                                $answer_value->text = $answerIndex+1;
                                $answer_value->save();
                            }
                            break;
                    }
                    $answerModel->solution_id = $answer_value->id;
                    $answerModel->score = $answer['score'];
                    $answerModel->save();
                }
            }
        }
        $this->saveSelectedResults($testModel->id);
        return  redirect()->route('test.index');
    }

    public function render()
    {
        return view('livewire.exam-task-creator');
    }

    public function updateTaskOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->tasks[$indexek[0]]['questions'][$indexek[1]]['answers'][$indexek[2]]);
        }
        $this->tasks[$indexek[0]]['questions'][$indexek[1]]['answers'] = $newAnswers;
    }
}
