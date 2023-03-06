<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;

class CoursesUsersController extends Controller
{
    public function store($selectedResults, Course $course) {
        try{
            foreach ($selectedResults as $selectedResult) {
                if (!$course->users->contains($selectedResult['id'])) {
                    $course->users()->attach($selectedResult['id']);
                }
            }
            return redirect()->route('courses.members', $course)->with('success', 'Teszt csoportjainak módosítása sikeresen megtörtént');
        }
        catch (\Exception $ex) {
            return redirect()->route('courses.members', $course)->with('fail', 'Valami hiba történt...');
        }
    }

    public function destroy($course_user_id)
    {
        try
        {
            $course_user = CoursesUsers::findOrFail($course_user_id);
            $course_user->delete();
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
    public function leave($course_user_id)
    {
        try
        {
            $course_user = CoursesUsers::findOrFail($course_user_id);
            $course_user->delete();
            Alert::success('Sikeresen kilépett a csoportból !');
            return redirect()->route('courses.index');
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>"Nem található a megadott ID-vel kapcsolat...."]);
        }
    }
}
