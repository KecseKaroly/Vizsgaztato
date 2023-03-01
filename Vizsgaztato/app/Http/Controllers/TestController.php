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
use App\Services\TestService;

class TestController extends Controller
{
    public function __construct(private TestService $testService)
    {
    }

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
            $testLiveWire = $this->testService->getTestToWrite($test, $groupId);
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
            $testLiveWire = $this->testService->getTestToEdit($test);
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
