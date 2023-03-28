<?php

namespace App\Mail;

use App\Models\group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserKickedFromGroupNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * @param group $group
     * @return void
     */
    public function __construct(group $group)
    {
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('support@examonline.com')->view('emails.userkickedfromgroup', ['group' => $this->group]);
    }
}
