<?php

namespace App\Http\Livewire;
use App\Models\test;
use App\Models\task;
use App\Models\question;
use App\Models\answer;
use App\Models\answer_value;
use App\Models\TestsGroups;
use Livewire\Component;
use App\Http\Controllers\TestsGroupsController;
use App\Models\group;

use Barryvdh\Debugbar\Facade as Debugbar;

class ExamTaskEdit extends Component
{
    public $deletedTasks = [];
    public $deletedQuestions = [];
    public $deletedAnswers = [];

    public $tasks = [];
    public $testTitle ='';
    public $testAttempts = 1;
    public $testId = 1;

    public $searchValue;
    public $searchResults;
    public $selectedResults;
    public $givenGroups;
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

    public function mount($testLiveWire, $groups) {
        $this->testId = $testLiveWire['id'];
        $this->testTitle = $testLiveWire['title'];
        $this->testAttempts = $testLiveWire['maxAttempts'];
        $this->tasks = $testLiveWire['tasks'];

        $this->selectedResults = $groups;
        $this->givenGroups = $groups;
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
        if(array_key_exists('id', $this->tasks[$taskIndex])) {
            array_push($this->deletedTasks, $this->tasks[$taskIndex]);
        }
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
        if($this->tasks[$index]["type"] == "OneChoice" || $this->tasks[$index]["type"] == "TrueFalse")
        {
            array_unshift($this->tasks[$index]['questions'],
            [
               'text' => '',
               'answers' => $answers,
               'right_answer_index' => '',
            ]
           );
        }
        else {
            array_unshift($this->tasks[$index]['questions'],
         [
            'text' => '',
            'answers' => $answers,
         ]
        );
        }

    }
    public function Remove_Question($taskIndex, $questionIndex) {
        if(array_key_exists('id', $this->tasks[$taskIndex]['questions'][$questionIndex])) {
            array_push($this->deletedQuestions, $this->tasks[$taskIndex]['questions'][$questionIndex]);
        }
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
        if(array_key_exists('id', $this->tasks[$taskIndex]['questions'][$questionIndex]['answers'][$answerIndex])) {
            array_push($this->deletedAnswers, $this->tasks[$taskIndex]['questions'][$questionIndex]['answers'][$answerIndex]);
        }
        unset($this->tasks[$taskIndex]['questions'][$questionIndex]['answers'][$answerIndex]);
    }
    public function Save_Test()
    {
        $testModel = test::find($this->testId);
        $testModel->title = $this->testTitle;
        $testModel->maxAttempts = $this->testAttempts;
        $testModel->save();

        foreach($this->tasks as $taskIndex => $task) {
            if(array_key_exists('id', $task)) {
                $taskModel = task::find($task['id']);
            }
            else $taskModel = new task;
            $taskModel->test_id = $testModel->id;
            $taskModel->text = $task['text'];
            $taskModel->type = $task['type'];
            $taskModel->save();

            foreach($task['questions'] as $questionIndex => $question) {
                if(array_key_exists('id', $question)) {
                    $questionModel = question::find($question['id']);
                }
                else $questionModel = new question;
                $questionModel->task_id = $taskModel['id'];
                $questionModel->text = $question['text'];
                $questionModel->save();

                foreach($question['answers'] as $answerIndex => $answer) {
                    $answerModel = answer::find($answer['id']);
                    if($answerModel == null) {
                        $answerModel = new answer;
                    }
                    $answerModel->question_id = $questionModel['id'];
                    $answerModel->text = $answer['text'];

                    switch($taskModel->type) {
                        case 'TrueFalse':
                            if($answer['id'] == $question['right_answer_index'])
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
                            if($answer['id'] == $question['right_answer_index'])
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
                            if($answer['solution'] != '' && $answer['solution'] >= 0)
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
        foreach($this->deletedTasks as $task) {
            $task = task::find($task['id']);
            $task->delete();
        }
        foreach($this->deletedQuestions as $question) {
            $question = question::find($question['id']);
            $question->delete();

        }
        foreach($this->deletedAnswers as $answer) {
            $answer = answer::find($answer['id']);
            $answer->delete();

        }
        foreach($this->givenGroups as $givenGroup) {
            if (!in_array($givenGroup, $this->selectedResults)) {
                $test_group = TestsGroups::where(['group_id'=>$givenGroup['id'], 'test_id'=>$this->testId]);
                $test_group->delete();
            }
        }

        return  redirect()->route('test.index');
    }

    public function render()
    {
        return view('livewire.exam-task-edit');
    }

    public function updateAnswerOrder($list) {
        $newAnswers = [];
        foreach($list as $element) {
            $indexek = explode("_",$element["value"]);
            array_push($newAnswers, $this->tasks[$indexek[0]]['questions'][$indexek[1]]['answers'][$indexek[2]]);
        }
        $this->tasks[$indexek[0]]['questions'][$indexek[1]]['answers'] = $newAnswers;
    }
}
