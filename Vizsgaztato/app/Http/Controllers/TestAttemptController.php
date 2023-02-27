<?php

namespace App\Http\Controllers;

use App\Models\testAttempt;
use Illuminate\Http\Request;
use App\models\test;

class TestAttemptController extends Controller
{
    public function index($testId, $groupId) {
        $test = test::find($testId)->load(['groups' => function ($query) use ($groupId) {
            $query->where('groups.id', $groupId)
                ->where('tests_groups.enabled_from', '<', now())
                ->where('tests_groups.enabled_until', '>', now());
        }]);
        $testAttempts = testAttempt::where(['user_id' => auth()->id(), 'test_id' => $test->id, 'group_id' => $groupId])->get();
        return view('testAttempts.index', ['testAttempts' => $testAttempts, 'test' => $test, 'group' => $groupId]);
    }

    public function show($attemptId) {
        $test = testAttempt::find($attemptId)->load('test')->test;
        $this->authorize('checkResult', $test);
        $attempt = testAttempt::findOrFail($attemptId)
            ->load([
                'test.questions.options.expected_answer',
                'test.questions.options.given_answers' => function ($query) use ($attemptId) {
                    $query->where('given_answers.attempt_id', '=', $attemptId);
                },
                'test.questions.options.given_answers.answer']);
        return view('testAttempts.student.show')->with('attempt', $attempt);
    }
    /**
     * Remove the specified resource from storage.
     *
     *  @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $testAttempt = testAttempt::find($request->testAttemptId);
        $testAttempt->delete();
        return response()->json(['success'=>'Próbálkozás sikeresen törölve']);
    }
}
