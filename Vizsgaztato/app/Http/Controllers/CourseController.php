<?php

namespace App\Http\Controllers;

use App\Events\CourseCreated;
use App\Http\Requests\StoreCourseRequest;
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
        $courses = auth()->user()->load('courses', 'groups.courses');

        $coursesToPass = $courses->courses;
        foreach($courses->groups as $group) {
            foreach($group->courses as $course) {
                $coursesToPass[] = $course;
            }
        }
        if($coursesToPass->unique()->isEmpty())
            $coursesToPass = Course::query()->whereNull('id');
        else
            $coursesToPass = $coursesToPass->unique()->toQuery();
        return view('courses.index', ['courses' => $coursesToPass->paginate(3)]);
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
    public function store(StoreCourseRequest $request)
    {
        try{
            $this->authorize('create', Course::class);
            $validated = $request->safe()->merge(['creator_id' => auth()->id()])->all();
            $course = Course::create($validated);

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
            $course->load('users', 'groups', 'quizzes');
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
    public function update(StoreCourseRequest $request, Course $course)
    {
        try{
            $this->authorize('update', $course);
            $course->update($request->validated());
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
