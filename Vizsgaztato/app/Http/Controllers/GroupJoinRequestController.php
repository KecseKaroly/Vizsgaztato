<?php

namespace App\Http\Controllers;
use App\Models\group_join_request;
use App\Models\group;
use App\Models\group_inv;
use App\Models\groups_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;

class GroupJoinRequestController extends Controller
{
    public function SubmitRequest(Request $request) {
        try{
            $group = group::where('invCode', $request->invCode)->firstOrFail();
            if(group_join_request::where(['group_id' => $group->id, 'requester_id'=> auth()->id()])->exists()) {
                return response()->json(['error'=>'Már rögzített csatlakozási kérelmet ebbe a csoportba!']);
            }
            if(groups_users::where(['group_id' => $group->id, 'user_id'=> auth()->id()])->exists()) {
                return response()->json(['error'=>'Már tagja ennek a csoportnak!']);
            }

            $group_join_request = new group_join_request;
            $group_join_request->requester_id = auth()->id();
            $group_join_request->group_id = $group->id;
            $group_join_request->save();

            return response()->json(['success'=>'Jelentkezési kérelem sikeresen rögzítve!',
                                     'message'=>'A csoport tulajdonosának el kell fogadnia a kérelmet']);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'A megadott kódhoz nem tartozik csoport!']);
        }
        catch(\Exception $exception){
            return response()->json(['error'=>'Valami hiba történt...']);
        }
    }

    public function index($group_id) {
        $join_requests = DB::table('group_join_requests')
            ->join('users', 'users.id', '=', 'group_join_requests.requester_id')
            ->select('users.name','group_join_requests.*')
            ->where('group_join_requests.group_id', $group_id)
            ->paginate(10);
        return view('groups.join_request.index')->with('join_requests', $join_requests);
    }

    public function AcceptRequest(Request $request) {
        try{
            $groups_users_connetion = groups_users::where(['user_id'=>$request->requester_id, 'group_id'=>$request->group_id]);
            if($groups_users_connetion->exists())
                return response()->json(['success'=>'Csatlakozási kérelem elfogadva']);
            
            $groups_users_connetion = new groups_users;
            $groups_users_connetion->user_id =  $request->requester_id;
            $groups_users_connetion->group_id =  $request->group_id;
            $groups_users_connetion->save();

            $join_request = group_join_request::find($request->join_request_id);
            $join_request->delete();

            $join_request = group_inv::where(['invited_id'=>$request->requester_id, 'group_id'=>$request->group_id]);
            if($join_request->exists())
                $join_request->delete();
            return response()->json(['success'=>'Csatlakozási kérelem elfogadva']);
        }
        catch(\Exception $exception) {
            return response()->json(['error'=>'Valami hiba történt...']);
        }

    }

    public function RejectRequest(Request $request) {
        try{
            $join_request = group_join_request::find($request->join_request_id);
            $join_request->delete();
            return response()->json(['success'=>'Csatlakozási kérelem elutasítva']);
        }
        catch(\Exception $exception) {
            return response()->json(['error'=>'Valami hiba történt...']);
        }
    }
}
