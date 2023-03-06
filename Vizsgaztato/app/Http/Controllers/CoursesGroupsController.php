<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesGroups;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;

class CoursesGroupsController extends Controller
{
    public function store($selectedResults, $deletedResults, Course $course) {
        try{
            foreach ($selectedResults as $selectedResult) {
                if (!$course->groups->contains($selectedResult['id'])) {
                    $course->groups()->attach($selectedResult['id']);
                }
            }
            foreach ($deletedResults as $selectedResult) {
                if ($course->groups->contains($selectedResult['id'])) {
                    $course->groups()->detach($selectedResult['id']);
                }
            }
        }
        catch (\Exception $ex) {
            return redirect()->route('courses.members', $course)->with('fail', 'Valami hiba történt...');
        }
    }

    public function destroy($course_group_id)
    {
        try
        {
            $course_group = CoursesGroups::findOrFail($course_group_id);
            $course_group->delete();
            return response()->json(['success'=>"Sikeres törlés"]);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['error'=>"Nem található a megadott ID-vel kapcsolat...."]);
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>"Valami hiba történt..."]);
        }
    }
}
