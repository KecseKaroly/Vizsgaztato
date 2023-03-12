<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\testAttempt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\models\test;
use RealRashid\SweetAlert\Facades\Alert;
class TestAttemptController extends Controller
{
    public function index(Course $course, test $test) {
        try{
            $this->authorize('viewResults', test::class);
            $submitted = testAttempt::where(['user_id' => auth()->id(), 'test_id' => $test->id, 'submitted'=>true])->orderBy('created_at', 'DESC')->get();
            $ongoing = testAttempt::where(['user_id' => auth()->id(), 'test_id' => $test->id, 'submitted'=>false])->orderBy('created_at', 'DESC')->get();
            return view('testAttempts.index', ['submitted' => $submitted, 'ongoing'=>$ongoing, 'test' => $test, 'course'=>$course]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }}


    public function show(Course $course, testAttempt $attempt) {
        try{
            $this->authorize('checkResult', $attempt->test);
            $attempt = $attempt
                ->load([
                    'test.questions.options.expected_answer',
                    'test.questions.options.given_answers' => function ($query) use ($attempt) {
                        $query->where('given_answers.attempt_id', '=', $attempt->id);
                    },
                    'test.questions.options.given_answers.answer']);
            return view('testAttempts.student.show', ['attempt'=>$attempt, 'course'=>$course]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('testAttempts.index', [$course, $attempt->test_id]);
        }
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
