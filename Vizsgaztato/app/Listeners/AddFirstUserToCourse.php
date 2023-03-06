<?php

namespace App\Listeners;

use App\Events\CourseCreated;
use App\Events\GroupCreated;
use App\Models\CoursesUsers;
use App\Models\groups_users;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddFirstUserToCourse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CourseCreated  $event
     * @return void
     */
    public function handle(CourseCreated $event)
    {
         $courses_users = new CoursesUsers([
             'user_id'=>auth()->id(),
             'course_id'=>$event->course->id,
         ]);
         $courses_users->save();
    }
}
