<?php

namespace App\Actions;

use App\Models\testAttempt;
use App\Models\answer;
use App\Models\given_answer;
use Session;
class StoreTestAttempt
{
    public function store($test): testAttempt
    {
        $attempt = testAttempt::find($test['attempt_id']);
        $attempt->submitted = true;
        $attempt->save();
        foreach ($test['questions'] as $questionIndex => $question) {
            foreach ($question['options'] as $optionIndex => $option) {
                switch ($question['type']) {
                    case "TrueFalse":
                    case "OneChoice":
                        $tempAns = $optionIndex == $question['actual_ans'] ? 'checked' : 'unchecked';
                        break;
                    case "MultipleChoice":
                        $tempAns = $option['actual_ans'] == '' ? "unchecked" : "checked";
                        break;
                    case "Sequence":
                        $tempAns = $optionIndex + 1;
                        break;
                }
                $answer = answer::where('solution', $tempAns)->first();
                $givenAnswer = new given_answer();
                $givenAnswer->attempt_id = $attempt->id;
                $givenAnswer->option_id = $option['id'];
                $givenAnswer->answer_id = $answer->id;
                $givenAnswer->save();
            }
        }
        Session::forget('attempt_'.$attempt->id);

        return $attempt;
    }
}
