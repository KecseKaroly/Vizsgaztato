<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
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
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Course $course)
    {
        return $course->users->contains($user)  || $course->groups()->users()->contains($user)
            ? Response::allow()
            : Response::deny('Jogosulatlan a kvízhez!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Course $course)
    {
        return $user == $course->creator
            ? Response::allow()
            : Response::deny('Nem Ön hozta létre a kurzust, ezért kvízt sem adhat hozzá!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Course $course)
    {
        return $user == $course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem módosíthatja a kvízt!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Course $course)
    {
        return $user == $course->creator
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem törölheti annak kvízeit!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Course $course)
    {
        return $user == $course->creator()
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem módosíthatja annak kvízeit!');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Course $course)
    {
        return $user == $course->creator()
            ? Response::allow()
            : Response::deny('Nem Ön a kurzus készítője, így nem törölheti annak kvízeit!');
    }
}
