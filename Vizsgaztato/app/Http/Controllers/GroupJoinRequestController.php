<?php

namespace App\Http\Controllers;
use Response;
use App\Models\group_join_request;
use App\Models\group;
use App\Models\group_inv;
use App\Models\groups_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupJoinRequestController extends Controller
{
    public function SubmitRequest(Request $request) {
        try{
            $group = group::where('invCode', $request->invCode)->firstOrFail();
            if(group_join_request::where(['group_id' => $group->id, 'requester_id'=> Auth::id()])->exists())
                return response()->json(['failed'=>'Már rögzítve lett a csoporthoz csatlakozási kérelem - elutasítva!']);
            if(groups_users::where(['group_id' => $group->id, 'user_id'=> Auth::id()])->exists())
                return response()->json(['failed'=>'Már tagja a csoportnak - elutasítva!']);

            $group_join_request = new group_join_request;
            $group_join_request->requester_id = Auth::id();
            $group_join_request->group_id = $group->id;
            $group_join_request->save();
            return response()->json(['success'=>'A csatlakozási kérelem sikeresen rögzítve']);
        }
        catch(\Exception $exception){
            return response()->json(['failed'=>'A megadott kóddal nincsen csoport rögzítve...']);
        }

    }

    public function index($group_id) {
        $join_requests = DB::table('group_join_requests')
            ->join('users', 'users.id', '=', 'group_join_requests.requester_id')
            ->select('users.name','group_join_requests.*')
            ->where('group_join_requests.group_id', $group_id)
            ->get();
        return view('groups.join_request.index')->with('join_requests', $join_requests);
    }

    public function AcceptRequest(Request $request) {
        $groups_users_connetion = new groups_users;
        $groups_users_connetion->user_id =  $request->requester_id;
        $groups_users_connetion->group_id =  $request->group_id;
        $groups_users_connetion->role = "member";
        $groups_users_connetion->save();

        $join_request = group_join_request::find($request->join_request_id);
        $join_request->delete();

        $join_request = group_inv::where(['invited_id'=>$request->requester_id, 'group_id'=>$request->group_id]);
        if($join_request->exists())
            $join_request->delete();
        return response()->json(['success'=>'Csatlakozási kérelem elfogadva']);
    }

    public function RejectRequest(Request $request) {
        $join_request = group_join_request::find($request->join_request_id);
        $join_request->delete();
        return response()->json(['success'=>'Csatlakozási kérelem elutasítva']);
    }
}
