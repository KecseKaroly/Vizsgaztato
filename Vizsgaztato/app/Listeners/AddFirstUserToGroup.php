<?php

namespace App\Listeners;

use App\Events\GroupCreated;
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
        //
    }
}
