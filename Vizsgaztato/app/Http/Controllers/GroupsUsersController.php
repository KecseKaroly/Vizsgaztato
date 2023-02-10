<?php

namespace App\Http\Controllers;

use App\Models\groups_users;
use Illuminate\Http\Request;

class GroupsUsersController extends Controller
{
    public function destroy(Request $request)
    {
        $group_user = groups_users::find($request->groups_users_id);
        $id = $group_user->id;
        $group_user->delete();

        return response()->json(['success'=>$id]);
    }

    public function leave(Request $request)
    {
        $group_user = groups_users::find($request->guid);
        $group_user->delete();
        return redirect()->route('groups.index');
    }
}
