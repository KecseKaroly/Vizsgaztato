<?php

namespace App\Events;

use App\Models\test;
use App\Models\testAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEnded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public testAttempt $attempt;
    /**
     * Create a new event instance.
     * @param testAttempt $attempt
     * @return void
     */
    public function __construct(testAttempt $attempt)
    {
        $this->attempt = $attempt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
