<?php

namespace App\Jobs;

use App\Mail\UserKickedFromGroupNotification;
use App\Models\group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyKickedUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $group;
    /**
     * Create a new job instance.
     * @param User $user
     * @param group $group
     * @return void
     */
    public function __construct(User $user, group $group)
    {
        $this->user = $user;
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new UserKickedFromGroupNotification($this->group));
    }
}
