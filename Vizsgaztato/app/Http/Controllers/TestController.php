<?php

namespace App\Http\Controllers;
use App\Models\group;
use App\Models\groups_users;
use App\Models\answer;
use App\Models\answer_value;
use App\Models\given_answer;
use App\Models\question;
use App\Models\task;
use App\Models\test;
use App\Models\User;
use App\Models\testAttempt;
use App\Models\TestsGroups;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myGroups = groups_users::where('user_id', Auth::id())->pluck('group_id')->toArray();
        $myGroupsTests = TestsGroups::whereIn('group_id', $myGroups)->distinct()->pluck('test_id')->toArray();

        $tests = test::whereIn('id', $myGroupsTests)->orWhereIn('creator_id', [Auth::id()])->get();
        return view('test.index')->with('tests', $tests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('test.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(test $test)
    {
        $testAttempts = testAttempt::where(['user_id' => Auth::id(), 'test_id' => $test->id])->count();
        if($testAttempts >= $test->maxAttempts)
            return view('test.write')->with('error', "Túllépte a megengedett próbálkozásokat!");

        $testLiveWire = [
            'id' => $test->id,
            'title' => $test->title,
            'tasks' => []
        ];
        $tasks = task::where('test_id', $test->id)->get();
        foreach($tasks as $taskIndex => $task) {
            array_push(
                $testLiveWire['tasks'],
                [
                    'id' => $task->id,
                    'text' => $task->text,
                    'type' => $task->type,
                    'questions' => [],
                ]);
            $questions = question::where('task_id', $task->id)->get();
            foreach($questions as $questionIndex => $question) {
                array_push(
                    $testLiveWire['tasks'][$taskIndex]['questions'],
                    [
                        'id' => $question->id,
                        'text' => $question->text,
                        'maxScore' => 0,
                        'answers' => [],
                        'achievedScore' => 0,
                        'actual_ans' => ''
                    ]);
                $answers = answer::where('question_id', $question->id)->get();
                foreach($answers as $answer) {
                    $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['maxScore'] += $answer->score;
                    $answer_value = answer_value::find($answer->solution_id);
                    array_push(
                        $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['answers'],
                        [
                            'id' => $answer->id,
                            'text' => $answer->text,
                            'expected_ans' => $answer_value->text,
                            'actual_ans' => '',
                            'score' => $answer->score
                        ]
                    );
                }
                shuffle($testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['answers']);
            }
            shuffle($testLiveWire['tasks'][$taskIndex]['questions']);
        }
        shuffle($testLiveWire['tasks']);
        return view('test.write')->with('testLiveWire', $testLiveWire);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit(test $test)
    {
        $testLiveWire = [
            'id' => $test->id,
            'title' => $test->title,
            'maxAttempts' => $test->maxAttempts,
            'tasks' => []
        ];

        $tasks = task::where('test_id', $test->id)->get();
        foreach($tasks as $taskIndex => $task) {
            array_push(
                $testLiveWire['tasks'],
                [
                    'id' => $task->id,
                    'text' => $task->text,
                    'type' => $task->type,
                    'questions' => [],
                ]);
            $questions = question::where('task_id', $task->id)->get();
            foreach($questions as $questionIndex => $question) {
                array_push(
                    $testLiveWire['tasks'][$taskIndex]['questions'],
                    [
                        'id' => $question->id,
                        'text' => $question->text,
                        'answers' => [],
                        'right_answer_index' => '',
                    ]);
                $answers = answer::where('question_id', $question->id)->get();
                foreach($answers as $answer) {
                    $answer_value = answer_value::find($answer->solution_id);
                    if($task['type'] != "OneChoice" && $task['type'] != "TrueFalse" && $task['type'] != "Sequence")
                    {
                        array_push(
                            $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['answers'],
                            [
                                'id' => $answer->id,
                                'text' => $answer->text,
                                'score' => $answer->score,
                                'solution' => $answer_value->text == "checked" ? $answer->id : '',
                            ]
                        );
                    }
                    else
                    {
                        array_push(
                            $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['answers'],
                            [
                                'id' => $answer->id,
                                'text' => $answer->text,
                                'score' => $answer->score
                            ]
                        );
                        if($task['type'] == "OneChoice" || $task['type'] == "TrueFalse") {
                            $solution = answer_value::find($answer->solution_id);
                            if($solution->text == "checked")
                                $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['right_answer_index'] = $answer->id;
                        }
                    }
                }
            }
        }

        $groupIds = TestsGroups::where('test_id',$test->id)->pluck('group_id')->toArray();

        $groups = DB::table('groups')
        ->join('tests_groups', 'tests_groups.group_id', '=', 'groups.id')
        ->select('groups.*')
        ->whereIn('groups.id', $groupIds)
        ->get()
        ->toArray();
        $groupArray = [];
        foreach($groups as $object)
        {
            array_push($groupArray, (array)$object);
        }
        return view('test.edit', ['testLiveWire'=>$testLiveWire, 'groups'=>$groupArray]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, test $test)
    {

        $attempts = testAttempt::where('test_id', $test->id);
        foreach($attempts as $attempt) {
            $given_answers = given_answer::where('attempt_id', $attempt->id);
            $maxScore = 0;
            $achievedScore = 0;
            foreach($given_answers as $given_answer) {
                $answer = answer::find($given_answer->answer_id);
                if($given_answer->given_id == $answer->solution_id)
                    $achievedScore += $answer->score;
                $maxScore = $answer->score;
            }
            $attempt->maxScore = $maxScore;
            $attempt->achievedScore = $achievedScore;
            $attempt->save();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(test $test)
    {
        //
    }

    public function showResult($testId, $attemptId) {
        $attempt = testAttempt::find($attemptId);
        if($attempt == null)
            return redirect('/test/'.$testId.'/result/');
        $test = test::find($testId);
        $testResult = [
            'id' => $test->id,
            'title' => $test->title,
            'tasks' => []
        ];
        $tasks = task::where('test_id', $test->id)->get();
        foreach($tasks as $taskIndex => $task) {
            array_push(
                $testResult['tasks'],
                [
                    'id' => $task->id,
                    'text' => $task->text,
                    'type' => $task->type,
                    'questions' => [],
                ]);
            $questions = question::where('task_id', $task->id)->get();
            foreach($questions as $questionIndex => $question) {
                array_push(
                    $testResult['tasks'][$taskIndex]['questions'],
                    [
                        'id' => $question->id,
                        'text' => $question->text,
                        'maxScore' => 0,
                        'achievedScore' => 0,
                        'answers' => []
                    ]
                );
                $given_answers = given_answer::where(['question_id' => $question->id, 'attempt_id'=> $attemptId])->get();
                foreach($given_answers as $given_answer) {
                    $given_answer_value = answer_value::find($given_answer->given_id);
                    $exp_answer = answer::find($given_answer->answer_id);
                    $exp_answer_value = answer_value::find($exp_answer->solution_id);
                    $testResult['tasks'][$taskIndex]['questions'][$questionIndex]['maxScore'] += $exp_answer->score;

                    if($task->type == 'Sequence') {
                        if($exp_answer_value->id == $given_answer_value->id)
                        {
                            $answer_class = 'correct';
                            $testResult['tasks'][$taskIndex]['questions'][$questionIndex]['achievedScore'] += $exp_answer->score;
                        }
                        else {
                            $answer_class = 'incorrect';
                        }
                    }
                    else {
                        $answer_class = "";
                        if($exp_answer_value->text == "checked" && $given_answer_value->text == "checked")
                        {
                            $answer_class = 'correct';
                            $testResult['tasks'][$taskIndex]['questions'][$questionIndex]['achievedScore'] += $exp_answer->score;
                        }
                        else if($given_answer_value->text != "checked" && $exp_answer_value->text == "checked") {
                            $answer_class = 'missed';
                        }
                        else if($given_answer_value->text == "checked" && $exp_answer_value->text != "checked") {
                            $answer_class = 'incorrect';
                        }
                    }
                    array_push(
                        $testResult['tasks'][$taskIndex]['questions'][$questionIndex]['answers'],
                            [
                                'id' => $exp_answer->id,
                                'text' => $exp_answer->text,
                                'expected_ans' => $exp_answer->solution,
                                'actual_ans' => '',
                                'score' => $exp_answer->score,
                                'answer_class' => $answer_class,
                                'given' => $given_answer->given
                            ]
                        );
                }

            }
        }

        return view('test.results.show')->with('test', $testResult);
    }

    public function testResults($testId) {
        $test = test::find($testId);
        if(testAttempt::where(['user_id' => Auth::id(), 'test_id' => $test->id])->count() == 0)
        {
            return view('test.results.index', ['noAttempts'=> 'Még nincsen a teszthez próbálkozása!', 'test'=>$test]);
        }
        else
        {
            $testAttempts = testAttempt::where(['user_id' => Auth::id(), 'test_id' => $test->id])->get();
            return view('test.results.index', ['testAttempts'=> $testAttempts, 'test'=>$test]);
        }
    }

    public function testInfo($testId) {
        $test = test::find($testId);
        if(testAttempt::where(['test_id' => $test->id])->count() == 0)
        {
            return view('test.info.show', ['noAttempts'=> 'Még nincsen a teszthez próbálkozás!', 'test'=>$test]);
        }
        else
        {
            $testAttempts = DB::table('test_attempts')
            ->join('users', 'users.id', '=', 'test_attempts.user_id')
            ->select('test_attempts.*', 'users.name')
            ->where('test_attempts.test_id', $testId)
            ->orderBy('user_id')
            ->get();

            $userIds = testAttempt::where('test_id', $testId)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->get();

            $groupIds = TestsGroups::where('test_id', $testId)->pluck('group_id')->toArray();
            $groups = group::whereIn('id', $groupIds)->get();

            $groups_users = groups_users::whereIn('group_id', $groupIds)->whereIn('user_id', $userIds)->get();
            return view('test.info.show', ['testAttempts'=> $testAttempts, 'test'=>$test, 'users'=>$users, 'groups'=>$groups, 'groups_users'=>$groups_users]);
        }
    }
}
