<?php

namespace App\Listeners;

use App\Events\TestEnded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateResults
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
     * @param  \App\Events\TestEnded  $event
     * @return void
     */
    public function handle(TestEnded $event)
    {
        //
    }
}
