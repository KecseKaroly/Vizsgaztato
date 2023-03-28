<?php

namespace App\Http\Controllers;

use App\Events\CourseCreated;
use App\Models\Course;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = DB::table('courses')->select('*')
            ->whereIn('id', function ($query) {
                $query->select('course_id')->from('courses_users')->where('user_id', auth()->id());
            })
            ->orWhereIn('id',auth()->user()->groups->pluck('id'))
            ->paginate(3);
        return view('courses.index', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $this->authorize('create', Course::class);
            return view('courses.create');
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $this->authorize('create', Course::class);
            $course = new course([
                'title'=>$request->name,
                'goal'=>$request->goal,
                'creator_id'=>auth()->id(),
            ]);
            $course->save();
            event(new CourseCreated($course));
            Alert::success('Kruzus sikeresen mentve!');
            return redirect()->route('courses.show', $course);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        try{
            $this->authorize('view', $course);
            //$course = $course->load('users', 'groups');
            $course->load('quizzes');
            return view('courses.show', ['course'=>$course]);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        try{
            $this->authorize('update', $course);
            return view('courses.edit', ['course'=>$course]);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        try{
            $this->authorize('update', $course);
            $course->title = $request->title;
            $course->goal = $request->goal;
            $course->save();
            Alert::success('Sikeres módosítás');
            return redirect()->route('courses.show', $course);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        try{
            $this->authorize('delete', $course);
            $course->delete();
            Alert::success('Kurzus sikeresen törölve!');
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());

        } finally {
            return redirect()->route('courses.index');
        }
    }

    /**
     * Show all the members of a given course
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function members(Course $course)
    {
        try{
            $this->authorize('view', $course);
            $id = $course->creator_id;
            $course->load(['groups.users' => function ($query) use($id){
                $query->where('user_id', '!=', $id);
            }, 'users']);
            return view('courses.members', ['course'=>$course]);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }
}
