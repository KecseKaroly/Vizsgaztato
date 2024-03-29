<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ModuleController extends Controller
{
    /**
     * Show all the modules of a given course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        try{
            $this->authorize('view', $course);
            $modules = $course->modules()->paginate(5);
            return view('modules.index', ['course'=>$course,'modules'=>$modules]);
        }
        catch (AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Course $course)
    {
        try{
            $this->authorize('create', $course);
            return view('modules.create', ['course'=>$course]);
        }
        catch (AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.show', $course);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        try{
            $this->authorize('create', Course::find($request->course_id));
            $validated = $request->safe()->merge(['course_id' => $request->course_id])->all();
            $module = Module::create($validated);
            Alert::success('Modul sikeresen létrehozva!');
            return redirect()->route('courses.modules', ['course'=>$module->course]);
        }
        catch (AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.show', $request->course_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        try{
            $this->authorize('view', $module);
            return view('modules.show', ['module'=>$module]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        try{
            $this->authorize('update', $module);
            return view('modules.edit', ['module'=>$module]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request, Module $module)
    {
        try{
            $this->authorize('update', $module);
            $module->update($request->validated());
            Alert::success('Modul sikeresen frissítve!');
            return view('modules.show', ['module'=>$module]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        try{
            $this->authorize('delete', $module);
            $module->delete();
            $course = $module->course;
            Alert::success('Modul sikeresen törölve!');
            return redirect()->route('courses.modules', ['course'=>$course]);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('courses.index');
        }

    }
}
