<?php

namespace App\Listeners;

use App\Events\GroupCreated;
use App\Models\groups_users;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddFirstUserToGroup
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
     * @param  \App\Events\GroupCreated  $event
     * @return void
     */
    public function handle(GroupCreated $event)
    {
         $groups_users = new groups_users([
             'user_id'=>auth()->id(),
             'group_id'=>$event->group->id,
             'is_admin'=>true,
         ]);
         $groups_users->save();
    }
}
