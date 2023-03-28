<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ModulePolicy
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
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Module $module)
    {
        $groups = $module->course->groups;
        if(count($groups)) {
            foreach($groups as $group) {
                if($group->users->contains($user))
                    return Response::allow();
            }
        }
        return $module->course->users->contains($user)
            ? Response::allow()
            : Response::deny('Jogosulatlan a modulhoz!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Course $course)
    {
        return $course->creator == $user;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Module $module)
    {
        return $user == $module->course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem módosíthatja a modult!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Module $module)
    {
        return $user == $module->course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem törölheti a modult!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Module $module)
    {
        return $user == $module->course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem módosíthatja a modult!');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Module $module)
    {
        return $user == $module->course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem törölheti a modult!');
    }
}
