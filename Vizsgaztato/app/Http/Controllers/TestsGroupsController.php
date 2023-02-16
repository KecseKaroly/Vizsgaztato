<?php

namespace App\Http\Controllers;

use App\Models\TestsGroups;
use App\Models\test;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TestsGroupsController extends Controller
{
    public function store($selectedResults, $deletedResults, test $test)
    {
        foreach ($selectedResults as $selectedResult) {
            if (!$test->groups->contains($selectedResult['id'])) {
                $test->groups()->attach($selectedResult['id']);
            }
        }
        foreach ($deletedResults as $selectedResult) {
            if ($test->groups->contains($selectedResult['id'])) {
                $test->groups()->detach($selectedResult['id']);
            }
        }
        return redirect()->route('checkTestInfo', $test);
    }

    /*public function delete(Request $request)
    {
        TestsGroups::destroy($request->test_group_id);
    }*/

    public function update(Request $request)
    {
        $test_group = TestsGroups::where(['test_id' => $request->test_id, 'group_id' => $request->group_id])->first();
        $test_group->enabled_from = $request->enabled_from;
        $test_group->enabled_until = $request->enabled_until;
        $test_group->save();
        return redirect()->route('checkTestInfo', $request->test_id);
    }
}
