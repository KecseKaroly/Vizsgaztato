<?php

namespace App\Http\Controllers;

use App\Models\TestsGroups;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TestsGroupsController extends Controller
{
    public function store($selectedResults, $testId) {
        foreach($selectedResults as $selectedResult) {
            if( !(TestsGroups::where(['test_id'=>$testId, 'group_id'=>$selectedResult['id']]))->exists())
            {
                $test_group = new TestsGroups();
                $test_group->test_id = $testId;
                $test_group->group_id = $selectedResult['id'];
                $test_group->save();
            }
        }
    }

    public function show($testId) {
        $test_groups = DB::table('tests_groups')
        ->join('groups', 'groups.id', '=', 'tests_groups.group_id')
        ->select('groups.*', 'tests_groups.id', 'tests_groups.enabled_from', 'tests_groups.enabled_until')
        ->where('tests_groups.test_id', $testId)
        ->get();
        return view('test.groups.show', ['test_groups'=>$test_groups, 'testId'=>$testId]);
    }

    public function delete(Request $request) {
        $test_group = TestsGroups::find($request->test_group_id);
        $test_group->delete();
    }

    public function update(Request $request) {
        $test_group = TestsGroups::find($request->test_group_id);
        $test_group->enabled_from = $request->enabled_from;
        $test_group->enabled_until = $request->enabled_until;
        $test_group->save();
    }
}
