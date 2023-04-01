<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesExams;
use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;
use App\Models\given_answer;
use Session;
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
    public function index(Course $course)
    {
        $tests = $course->exams()->paginate(10);
        return view('test.index', ['tests' => $tests, 'course'=>$course]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course)
    {
        try{
            $this->authorize('create', [test::class, $course]);
            return view('test.create', ['course'=>$course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index', $course);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function show(test $test)
    {
        try{
            $this->authorize('view', [$test]);
            $this->authorize('write', [$test]);
            $test = $test->load('questions.options.expected_answer');
            $course = $test->course;
            $attempt = testAttempt::where([
                'test_id'=>$test->id,
                'user_id'=>auth()->id(),
                'submitted'=>'0'])->first();
            if(!$attempt)
            {
                $testLiveWire = $this->testService->getTestToWrite($test);
            }
            else
            {
                if(Session::has('attempt_'. $attempt->id)) {
                    $testLiveWire = Session::get('attempt_' . $attempt->id);
                }
                else {
                    $testLiveWire = $this->testService->getTestToWrite($test, $attempt);
                }
            }
            return view('test.write', ['testLiveWire' => $testLiveWire, 'course' => $course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course, test $test)
    {
        try{
            $this->authorize('update', $test);
            $test = $test->load([
                'questions.options' => function ($query) {
                    $query->orderBy('options.expected_answer_id', 'ASC');
                },
                'questions.options.expected_answer']);
            $testLiveWire = $this->testService->getTestToEdit($test);
            return view('test.edit', ['testLiveWire' => $testLiveWire, 'course'=>$course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }

    }

    public function update(Request $request) {
        $test = test::find($request->test_id);
        $test->enabled_from = $request->enabled_from;
        $test->enabled_until = $request->enabled_until;
        $test->save();
        return redirect()->back();
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
            return redirect()->route('courses.index');
        }

    }
    public function testInfo(Course $course, test $test)
    {
        try{
            $this->authorize('checkInfo', $test);
            $course->load([
                'users' => function($query) use ($test, $course) {
                    $query->where('users.id', '!=', $course->creator_id);
                },
               'users.attempts' => function($query) use ($test) {
                    $query->where('test_attempts.test_id', $test->id);
               },
                'groups.users' => function($query) use ($test, $course) {
                    $query->where('users.id', '!=', $course->creator_id);
                },
                'groups.users.attempts' => function($query) use ($test) {
                    $query->where('test_attempts.test_id', $test->id);
                },
            ]);

            $courseAllUsers = $course->users;
            foreach($course->groups as $group) {
                $courseAllUsers = $courseAllUsers->merge($group->users);
            }
            $course->users = $courseAllUsers;
            return view('testAttempts.show', ['test' => $test, 'course'=>$course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }
}
