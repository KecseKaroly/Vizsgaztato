<?php

namespace App\Policies;

use App\Models\User;
use App\Models\group;
use App\Models\groups_users;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, group $group)
    {
        $groupOfUser = groups_users::where(['user_id' => $user->id, 'group_id' => $group->id])->get();
        return !$groupOfUser->isEmpty()
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem tagja ennek a csoportnak!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return !$user->is_student
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet diákoknak!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, group $group)
    {
        return $user->id === $group->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a csoport!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, group $group)
    {
        return $user->id === $group->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a csoport!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, group $group)
    {
        return $user->id === $group->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a csoport!');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, group $group)
    {
        return $user->id === $group->creator_id
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! Nem Önhöz tartozik ez a csoport!');
    }
}
