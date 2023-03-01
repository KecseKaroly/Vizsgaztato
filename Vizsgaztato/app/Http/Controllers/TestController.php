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
use Illuminate\Auth\Access\AuthorizationException;
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
        if(auth()->user()->is_student){
            $groups = auth()->user()->load(['groups.tests'])->groups;
            return view('test.index', ['groups' => $groups]);
        }
        $tests = test::whereIn('creator_id', [auth()->id()])->get();
        return view('test.index', ['tests' => $tests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $this->authorize('create', test::class);
            return view('test.create');
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
        }

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
    public function show($testId, $groupId)
    {
        try{
            $test = test::find($testId);
            $group = group::find($groupId);
            $this->authorize('view', [$test, $group]);

            $test = $test->load('questions.options.expected_answer');
            $testAttempts = testAttempt::where(['user_id' => auth()->id(), 'test_id' => $test->id, 'group_id' => $groupId])->count();
            if ($testAttempts >= $test->maxAttempts) {
                Alert::danger("Túllépte a megengedett próbálkozásokat!");
                return view('test.write');
            }
            $testLiveWire = [
                'group_id' => $groupId,
                'id' => $test->id,
                'title' => $test->title,
                'duration' => $test->duration,
                'resultsViewable'=>$test->resultsViewable,
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
                        'expected_ans' => $answer->solution,
                        'actual_ans' => '',
                        'score' => $option->score
                    ];
                }
                shuffle($testLiveWire['questions'][$questionIndex]['options']);
            }
            shuffle($testLiveWire['questions']);
            return view('test.write')->with('testLiveWire', $testLiveWire);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function edit(test $test)
    {
        try{
            $this->authorize('update', $test);
            $test = $test->load([
                'questions.options' => function ($query) {
                    $query->orderBy('options.expected_answer_id', 'ASC');
                },
                'questions.options.expected_answer']);
            $testLiveWire = [
                'id' => $test->id,
                'title' => $test->title,
                'maxAttempts' => $test->maxAttempts,
                'duration' => $test->duration,
                'resultsViewable' => $test->resultsViewable,
                'questions' => []
            ];
            foreach ($test->questions as $questionIndex => $question) {
                $testLiveWire['questions'][] = [
                    'id' => $question->id,
                    'type' => $question->type,
                    'text' => $question->text,
                    'options' => [],
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
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
        }

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
        dd('test.update');
        try{
            $this->authorize('update', $test);
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
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
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
        try{
            $this->authorize('delete', $test);
            $test->delete();
            Alert::success('A vizsgasor sikeresen törölve!');
            return back();
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
        }

    }
    public function testInfo($testId)
    {
        try{
            $this->authorize('checkInfo', test::findOrFail($testId));
            $test = test::with(
                [
                    'groups.users' => function ($query) use ($testId) {
                        $query->where('groups_users.is_admin', 'like', '0');
                    },
                    'groups.users.attempts' => function ($query) use ($testId) {
                        $query->where('test_attempts.test_id', 'like', $testId);
                    },
                ]
            )
                ->where('id', $testId)->first();
            return view('testAttempts.show', ['test' => $test]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index');
        }
    }
}
