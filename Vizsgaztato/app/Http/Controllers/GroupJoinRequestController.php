<?php

namespace App\Http\Controllers;
use Response;
use App\Models\group_join_request;
use App\Models\group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupJoinRequestController extends Controller
{
    public function SubmitRequest(Request $request) {
        $group = group::where('invCode', $request->invCode)->first();
        if(!group_join_request::where(
                ['group_id' => $group->id, 'requester_id'=> Auth::id()]
            )->exists())
        {
            $group_join_request = new group_join_request;
            $group_join_request->requester_id = Auth::id();
            $group_join_request->group_id = $group->id;
            $group_join_request->save();
        }
        return response()->json(['success'=>'Successfully']);
    }

    public function SubmitRequest2(Request $request) {
        dd("ASD");
        $group = group::where('invCode', $request->invCode)->get();
        if(!group_join_request::where(
                ['group_id' => $group->id, 'requester_id'=> Auth::id()]
            )->exists())
        {
            $group_join_request = new group_join_request;
            $group_join_request->requester_id = Auth::id();
            $group_join_request->group_id = $group->id;
            $group_join_request->save();
        }
        return response()->json(['success'=>'Successfully']);
    }
}
