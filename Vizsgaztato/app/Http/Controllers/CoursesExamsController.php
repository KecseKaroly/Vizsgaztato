<?php

namespace App\Http\Controllers;

use App\Models\CoursesExams;
use Illuminate\Http\Request;

class CoursesExamsController extends Controller
{
    public function update(Request $request)
    {
        $course_exam = CoursesExams::where(['test_id' => $request->test_id, 'course_id' => $request->course_id])->first();
        $course_exam->enabled_from = $request->enabled_from;
        $course_exam->enabled_until = $request->enabled_until;
        $course_exam->save();
        return redirect()->back();
    }
}
