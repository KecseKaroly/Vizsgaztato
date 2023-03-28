<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyKickedUser;
use App\Mail\UserKickedFromGroupNotification;
use App\Models\group;
use App\Models\groups_users;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Alert;
use Illuminate\Support\Facades\Mail;

class GroupsUsersController extends Controller
{
    public function destroy($groups_users_id)
    {
        try
        {
            $group_user = groups_users::findOrFail($groups_users_id);
            $user = User::find($group_user->user_id);
            $group = group::find($group_user->group_id);
            $group_user->delete();
            NotifyKickedUser::dispatch($user, $group);
            return response()->json(['success'=>"Sikeres törlés\nA felhasználót emailben értesítjük a kirúgásáról."]);
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
