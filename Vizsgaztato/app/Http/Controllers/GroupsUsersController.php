<?php

namespace App\Http\Controllers;

use App\Models\groups_users;
use Illuminate\Http\Request;

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
        catch(\Exception $e)
        {
            return response()->json(['fail'=>"Nem található a megadott ID-vel kapcsolat...."]);
        }
    }
    public function leave($groups_users_id)
    {
        try
        {
            $group_user = groups_users::findOrFail($groups_users_id);
            $group_user->delete();
            return redirect()->route('groups.index')->with('message', 'A csoportból sikeresen kilépett!');
        }
        catch(\Exception $e)
        {
            return response()->json(['fail'=>"Nem található a megadott ID-vel kapcsolat...."]);
        }
    }
}
