<?php

namespace App\Http\Controllers;
use App\Models\answer;
use App\Models\answer_value;
use App\Models\given_answer;
use App\Models\question;
use App\Models\task;
use App\Models\test;
use App\Models\testAttempt;
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
        $tests = test::all();
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
            return view('test.write')->with('error', "Too many attempts!");

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
                if($task['type'] == "OneChoice" || $task['type'] == "TrueFalse")
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
                else
                    array_push(
                        $testLiveWire['tasks'][$taskIndex]['questions'], 
                        [
                            'id' => $question->id,
                            'text' => $question->text,
                            'maxScore' => 0,
                            'achievedScore' => 0,
                            'answers' => []
                        ]);
                $answers = answer::where('question_id', $question->id)->get();
                foreach($answers as $answer) {
                    $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['maxScore'] += $answer->score;
                    $answer_value = answer_value::find($answer->solution_id);
                    if($task['type'] != "OneChoice" || $task['type'] != "TrueFalse")
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
                    else
                        array_push(
                            $testLiveWire['tasks'][$taskIndex]['questions'][$questionIndex]['answers'],
                            [
                                'id' => $answer->id,
                                'text' => $answer->text,
                                'expected_ans' => $answer_value->text,
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
        //
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
        //
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
                $given_answers = given_answer::where(['question_id' => $question->id, 'attempt_id'=> $attemptId])->orderBy('given_id')->get();
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
                        Debugbar::log([$exp_answer->solution, $given_answer->given, $answer_class]);
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
}
