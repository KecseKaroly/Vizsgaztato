<?php

namespace App\Http\Controllers;

use App\Models\groups_users;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Alert;

class GroupsUsersController extends Controller
{
    public function destroy($groups_users_id)
    {
        try
        {
            $group_user = groups_users::findOrFail($groups_users_id);
            $group_user->delete();
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
    public function leave($groups_users_id)
    {
        try
        {
            $group_user = groups_users::findOrFail($groups_users_id);
            $group_user->delete();
            Alert::success('Sikeresen kilépett a csoportból !');
            return redirect()->route('groups.index');
        }
        catch(\Exception $e)
        {
            return response()->json(['error'=>"Nem található a megadott ID-vel kapcsolat...."]);
        }
    }
}
