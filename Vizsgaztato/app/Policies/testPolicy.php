<?php

namespace App\Policies;

use App\Models\groups_users;
use App\Models\testAttempt;
use App\Models\TestsGroups;
use App\Models\User;
use App\Models\test;
use App\Models\group;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class testPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, test $test, group $group)
    {
        $user_group = groups_users::where(['group_id'=>$group->id, 'user_id'=>$user->id])->first();
        if($user_group == null)
            return Response::deny('Ön nem tagja a megadott csoportnak!');
        $test_group = TestsGroups::where(['test_id'=>$test->id, 'group_id'=>$group->id])->first();
        if($test_group == null)
            return Response::deny('A csoportja nem férhet hozzá ehhez a teszthez!');
        if(!($test_group->enabled_from < now() && $test_group->enabled_until > now()))
            return Response::deny('A csoportjának lejárt az ideje ehhez a teszthez!');
        if($test->maxAttempts <= testAttempt::where(['user_id'=>$user->id,'test_id'=>$test->id, 'group_id'=>$group->id])->count())
            return Response::deny('Meghaladta a maximális kitöltések számát!');
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a teszt!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a teszt!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a teszt!');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a teszt!');
    }

    /**
     * Determine whether the user can view the info of the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function checkInfo(User $user, test $test)
    {
        return $user->id === $test->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a teszt!');
    }

    /**
     * Determine whether the user can view the result of the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function checkResult(User $user, test $test)
    {
        return $test->resultsViewable
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! A feladatok megoldásai nem publikusak!');
    }
}
