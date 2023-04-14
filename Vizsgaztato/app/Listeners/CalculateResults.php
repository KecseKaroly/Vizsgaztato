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
     * @param \App\Events\TestEnded $event
     * @return void
     */
    public function handle(TestEnded $event)
    {
        $attempt = $event->attempt->load(['test.questions.options.expected_answer',
            'test.questions.options.given_answers' => function ($query) use ($event) {
                $query->where('attempt_id', $event->attempt->id);
            }]);
        foreach ($attempt->test->questions as $question) {
            $countCorrect = true;
            $achievedInQuestion = 0;
            foreach ($question->options as $option) {
                if ($question->type != 'Sequence') {
                    if ($option->given_answers[0]->answer == $option->expected_answer && $option->expected_answer->solution == "checked") {
                        $option->given_answers[0]->result = 1;  //correct
                        $option->given_answers[0]->save();
                        $achievedInQuestion++;
                        $attempt->maxScore++;
                    } else if ($option->given_answers[0]->answer != $option->expected_answer && $option->expected_answer->solution == "unchecked") {
                        if($question->type == "MultipleChoice");
                            $countCorrect = false;
                        $option->given_answers[0]->result = 2;  //incorrect
                        $option->given_answers[0]->save();
                    } else if ($option->given_answers[0]->answer != $option->expected_answer && $option->expected_answer->solution == "checked") {
                        $option->given_answers[0]->result = 3;  //missed
                        $option->given_answers[0]->save();
                        $attempt->maxScore++;
                    }
                } else {
                    if ($option->given_answers[0]->answer == $option->expected_answer) {
                        $option->given_answers[0]->result = 1;  //correct
                        $option->given_answers[0]->save();
                        $achievedInQuestion++;
                    } else {
                        $option->given_answers[0]->result = 2;  //incorrect
                        $option->given_answers[0]->save();
                    }
                    $attempt->maxScore++;
                }
            }
            if(!$countCorrect)
                $achievedInQuestion = 0;
            $attempt->achievedScore+=$achievedInQuestion;
        }
        $attempt->save();
    }
}
