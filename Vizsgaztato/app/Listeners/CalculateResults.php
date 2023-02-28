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
        $attempt = $event->attempt->load('given_answers.answer', 'given_answers.option.expected_answer', 'given_answers.option.question');
        $maxPoint = 0;
        $achieved = 0;
        foreach($attempt->given_answers as $given_answer) {

            if($given_answer->option->question->type != 'Sequence')
            {
                if($given_answer->answer == $given_answer->option->expected_answer && $given_answer->option->expected_answer->solution == "checked")
                {
                    $given_answer->result = 1;  //correct
                    $given_answer->save();
                }
                else if($given_answer->answer != $given_answer->option->expected_answer && $given_answer->option->expected_answer->solution == "unchecked")
                {
                    $given_answer->result = 2;  //incorrect
                    $given_answer->save();
                }
                else if($given_answer->answer != $given_answer->option->expected_answer && $given_answer->option->expected_answer->solution == "checked")
                {
                    $given_answer->result = 3;  //missed
                    $given_answer->save();
                }
            }
            else {
                if($given_answer->answer == $given_answer->option->expected_answer)
                {
                    $given_answer->result = 1;  //correct
                    $given_answer->save();
                }
                else
                {
                    $given_answer->result = 2;  //incorrect
                    $given_answer->save();
                }
            }
        }
    }
}
