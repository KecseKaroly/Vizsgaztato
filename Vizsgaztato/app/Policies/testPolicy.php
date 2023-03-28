<?php

namespace App\Policies;

use App\Models\Course;
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
    public function viewAny(User $user, Course $course)
    {
        $groups = $course->groups;
        if(count($groups)) {
            foreach($groups as $group) {
                if($group->users->contains($user))
                    return Response::allow();
            }
        }
        return $course->users->contains($user)
            ? Response::allow()
            : Response::deny('Jogosulatlan a teszthez!');

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, test $test)
    {
        $course = $test->course;
        $groups = $course->groups;
        if(count($groups)) {
            foreach($groups as $group) {
                if($group->users->contains($user))
                    return Response::allow();
            }
        }
        return $course->users->contains($user)
            ? Response::allow()
            : Response::deny('Jogosulatlan a teszthez!');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\test $test
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewResults(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createForCourse(User $user, Course $course)
    {
        return $user == $course->creator
            ? Response::allow()
            : Response::deny('Nem Ön hozta létre a kurzust, ezért tesztet sem adhat hozzá!');

    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return !$user->auth
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet diákoknak!');
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
        return $test->creator_id == $user->id || $test->resultsViewable
            ? Response::allow()
            : Response::deny('Nem engedélyezett művelet! A feladatok megoldásai nem publikusak!');
    }

    public function write(User $user, test $test)
    {
        $attempts = $user->load(['attempts' => function ($query) use ($test) {
            $query->where('test_attempts.test_id', $test->id);
        }])->attempts;
        if ($test->enabled_from >= now() || $test->enabled_until <= now())
            return Response::deny('A teszt kitöltése nincs engedélyezve ebben az időpontban!');
        return $attempts->count() < $test->maxAttempts || count($attempts->where('submitted', 0))
            ? Response::allow()
            : Response::deny('Elérte a maximális kitöltések számát!');
    }
}
