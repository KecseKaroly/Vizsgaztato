<?php

namespace App\Http\Controllers;
use Session;
use View;
use App\Models\group_inv;
use App\Models\group;
use App\Models\group_join_request;
use App\Models\groups_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupInvController extends Controller
{
   public function store($selectedResults, $groupId) {
        foreach($selectedResults as $selectedResult) {
            if( !(groups_users::where(['group_id'=>$groupId, 'user_id'=>$selectedResult['id']]))->exists() &&
                !(group_inv::where(['group_id'=>$groupId, 'invited_id'=>$selectedResult['id']]))->exists())
            {
                $group_inv = new group_inv;
                $group_inv->sender_id = Auth::id();
                $group_inv->group_id = $groupId;
                $group_inv->invited_id = $selectedResult['id'];
                $group_inv->save();
            }
        }
   }

   public function index() {
    $inv_requests = DB::table('group_invs')
        ->join('users', 'users.id', '=', 'group_invs.sender_id')
        ->join('groups', 'groups.id', '=', 'group_invs.group_id')
        ->select('users.name as USERNAME', 'groups.name', 'group_invs.*')
        ->where('group_invs.invited_id', Auth::id())
        ->get();
    return view('groups.inv_request.index')->with('inv_requests', $inv_requests);
}

public function AcceptRequest(Request $request) {
    try{
        $groups_users_connetion = new groups_users;
        $groups_users_connetion->user_id =  $request->invited_id;
        $groups_users_connetion->group_id =  $request->group_id;
        $groups_users_connetion->role = "member";
        $groups_users_connetion->save();

        $inv_request = group_inv::find($request->inv_request_id);
        $inv_request->delete();

        $join_request = group_join_request::where(['requester_id'=>$request->invited_id, 'group_id'=>$request->group_id]);
        if($join_request->exists())
            $join_request->delete();

        return response()->json(['success'=>'Meghívás elfogadva!']);
    }
    catch(\Exception $exception)
    {
        return response()->json(['error'=>'Valami hiba történt...']);
    }
}

public function RejectRequest(Request $request) {
    try{
        $inv_request = group_inv::find($request->inv_request_id);
        $inv_request->delete();
        return response()->json(['success'=>'Meghívás elutasítva']);
    }
    catch(\Exception $exception) {
        return response()->json(['error'=>'Valami hiba történt...']);
    }

}
}
