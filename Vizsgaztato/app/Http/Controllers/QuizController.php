<?php

namespace App\Http\Controllers;

use App\Models\answer;
use App\Models\Course;
use App\Models\given_answer;
use App\Models\group;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\test;
use App\Models\testAttempt;
use App\Services\TestService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class QuizController extends Controller
{
    public function __construct(private TestService $testService)
    {
    }
    /**
     * Show all the modules of a given quiz.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        try{
            $this->authorize('viewAny', [test::class, $course]);
            $quizzes = $course->quizzes()->with('module')->paginate(10);
            return view('quizzes.index', ['course'=>$course, 'quizzes'=>$quizzes]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.show', $course);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course, Module $module)
    {
        try{
            $this->authorize('create', [Quiz::class, $course]);
            return view('quizzes.create', ['course'=>$course, 'module'=>$module]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.show', $course);
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
    public function show(test $test, Course $course)
    {
        try{
            $this->authorize('view', [$test, $course]);
            $test = $test->load('questions.options.expected_answer');
            $course = $test->course;
            $testLiveWire = $this->testService->getTestToWrite($test);
            return view('quizzes.show', ['testLiveWire' => $testLiveWire, 'course' => $course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index', $course);
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
            $this->authorize('update', [$test, $course]);
            $test = $test->load([
                'questions.options' => function ($query) {
                    $query->orderBy('options.expected_answer_id', 'ASC');
                },
                'questions.options.expected_answer']);
            $testLiveWire = $this->testService->getTestToEdit($test);
            return view('quizzes.edit', ['testLiveWire' => $testLiveWire, 'course'=>$course]);
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index', $course);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\test $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(test $test, Course $course)
    {
        try{
            $this->authorize('delete', $test);
            $test->delete();
            Alert::success('A kvíz sikeresen törölve!');
            return back();
        }
        catch (AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('test.index', $course);
        }

    }
}
