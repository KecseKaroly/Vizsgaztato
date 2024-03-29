<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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
        $groups = auth()->user()->load('groups')->groups->take(4);
        return view('layouts.home', ['courses'=>$coursesToPass->unique()->take(6), 'groups'=>$groups]);
    }
}
