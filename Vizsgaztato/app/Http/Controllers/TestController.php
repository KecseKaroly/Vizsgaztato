<?php

namespace App\Http\Controllers;

use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;

use App\Models\User;
use App\Models\group;
use App\Models\groups_users;
use App\Models\TestsGroups;
use App\Models\testAttempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Alert;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user()->load('groups');
        $myGroupIds = $user->groups->modelKeys();
        $myGroupsTestIds = TestsGroups::whereIn('group_id', $myGroupIds)->distinct()->pluck('test_id')->toArray();

        $tests = test::whereIn('id', $myGroupsTestIds)->whereNotIn('creator_id', [Auth::id()])->get();
        $myTests = test::whereIn('creator_id', [Auth::id()])->get();
        return view('test.index', ['tests' => $tests, 'myTests' => $myTests]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function show(test $test)
    {
        $test = $test->load('questions.options.expected_answer');
        $testAttempts = testAttempt::where(['user_id' => Auth::id(), 'test_id' => $test->id])->count();
        if ($testAttempts >= $test->maxAttempts) {
            Alert::danger("Túllépte a megengedett próbálkozásokat!");
            return view('test.write');
        }
        $testLiveWire = [
            'id' => $test->id,
            'title' => $test->title,
            'duration' => $test->duration,
            'questions' => []
        ];
        foreach ($test->questions as $questionIndex => $question) {
            $testLiveWire['questions'][] = [
                'id' => $question->id,
                'text' => $question->text,
                'maxScore' => 0,
                'type' => $question->type,
                'options' => [],
                'achievedScore' => 0,
                'actual_ans' => ''
            ];
            foreach ($question->options as $option) {
                $testLiveWire['questions'][$questionIndex]['maxScore'] += $option->score;
                $answer = $option->expected_answer;
                $testLiveWire['questions'][$questionIndex]['options'][] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'expected_ans' => $answer->text,
                    'actual_ans' => '',
                    'score' => $option->score
                ];
            }
            shuffle($testLiveWire['questions'][$questionIndex]['options']);
        }
        shuffle($testLiveWire['questions']);
        return view('test.write')->with('testLiveWire', $testLiveWire);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function edit(test $test)
    {
        $test = $test->load('questions.options.expected_answer');
        $testLiveWire = [
            'id' => $test->id,
            'title' => $test->title,
            'maxAttempts' => $test->maxAttempts,
            'duration' => $test->duration,
            'tasks' => []
        ];
        foreach ($test->questions as $questionIndex => $question) {
            $testLiveWire['questions'][] = [
                'id' => $question->id,
                'type' => $question->type,
                'text' => $question->text,
                'answers' => [],
                'right_answer_index' => '',
            ];
            foreach ($question->options as $optionIndex => $option) {
                $answer = $option->expected_answer;
                if ($question['type'] == "MultipleChoice") {
                    $testLiveWire['questions'][$questionIndex]['options'][] = [
                        'id' => $option->id,
                        'text' => $option->text,
                        'score' => $option->score,
                        'solution' => $answer->solution == "checked" ? $answer->id : '',
                    ];
                } else {
                    $testLiveWire['questions'][$questionIndex]['options'][] = [
                        'id' => $option->id,
                        'text' => $option->text,
                        'score' => $option->score
                    ];
                    if ($question['type'] == "OneChoice" || $question['type'] == "TrueFalse") {
                        if ($answer->solution == "checked")
                            $testLiveWire['questions'][$questionIndex]['right_option_index'] = $optionIndex;
                    }
                }
            }
        }
        return view('test.edit', ['testLiveWire' => $testLiveWire]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, test $test)
    {
        $attempts = testAttempt::where('test_id', $test->id);
        foreach ($attempts as $attempt) {
            $given_answers = given_answer::where('attempt_id', $attempt->id);
            $maxScore = 0;
            $achievedScore = 0;
            foreach ($given_answers as $given_answer) {
                $answer = answer::find($given_answer->answer_id);
                if ($given_answer->given_id == $answer->solution_id)
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
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(test $test)
    {
        $test->delete();
        Alert::success('A vizsgasor sikeresen törölve!');
        return back();
    }

    public function showResult($attemptId)
    {
        $attempt = testAttempt::findOrFail($attemptId)
            ->load([
                'test.questions.options.expected_answer',
                'test.questions.options.given_answers' => function ($query) use ($attemptId) {
                    $query->where('given_answers.attempt_id', '=', $attemptId);
                },
                'test.questions.options.given_answers.answer']);
        return view('test.results.show')->with('attempt', $attempt);
    }

    public function testResults($testId)
    {
        $test = test::find($testId);
        $testAttempts = testAttempt::where(['user_id' => Auth::id(), 'test_id' => $test->id])->get();
        return view('test.results.index', ['testAttempts' => $testAttempts, 'test' => $test]);
    }

    public function testInfo($testId)
    {
        $test = test::with(
            [
                'groups.users' => function ($query) use ($testId) {
                    $query->where('groups_users.role', 'not like', 'admin');
                },
                'groups.users.attempts' => function ($query) use ($testId) {
                    $query->where('test_attempts.test_id', 'like', $testId);
                },
            ]
        )
            ->where('id', $testId)->first();
        return view('test.info.show', ['test' => $test]);
    }
}
