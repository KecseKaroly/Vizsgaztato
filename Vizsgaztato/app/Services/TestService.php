<?php

namespace App\Services;

use App\Models\test;
use App\Models\question;
use App\Models\option;
use App\Models\answer;

class TestService
{

    public function store(test $test, $questions)
    {

        foreach ($questions as $questionIndex => $question) {
            $questionModel = new question;
            $questionModel->text = $question['text'];
            $questionModel->type = $question['type'];
            $questionModel->test_id = $test->id;
            $questionModel->save();

            foreach ($question['options'] as $optionIndex => $option) {
                $optionModel = new option;
                $optionModel->question_id = $questionModel['id'];
                $optionModel->text = $option['text'];
                $answer = null;

                switch ($questionModel->type) {
                    case 'TrueFalse':
                    case 'OneChoice':
                        if ($optionIndex == $question['right_option_index']) {
                            $answer = answer::firstOrCreate(['solution' => 'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution' => 'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'MultipleChoice':
                        if ($option['solution'] >= 0) {
                            $answer = answer::firstOrCreate(['solution' => 'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution' => 'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'Sequence':
                        $answer = answer::firstOrCreate(['solution' => $optionIndex + 1]);
                        break;
                }
                $optionModel->expected_answer_id = $answer->id;
                $optionModel->score = $option['score'];
                $optionModel->save();
            }
        }
    }

    public function update(test $test, $questions) {
        foreach ($questions as $questionIndex => $question) {
            if(array_key_exists('id', $question)) {
                $questionModel = question::find($question['id']);
            }
            else $questionModel = new question;
            $questionModel->type =$question['type'];
            $questionModel->text =$question['text'];
            $questionModel->test_id = $test->id;
            $questionModel->save();
            foreach ($question['options'] as $optionIndex => $option) {
                $answer = null;
                switch ($questionModel->type) {
                    case 'TrueFalse':
                    case 'OneChoice':
                        if ($optionIndex == $question['right_option_index']) {
                            $answer = answer::firstOrCreate(['solution'=>'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution'=>'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'MultipleChoice':
                        if ($option['solution'] >= 0) {
                            $answer = answer::firstOrCreate(['solution'=>'checked']);
                            $option['score'] = 1;
                        } else {
                            $answer = answer::firstOrCreate(['solution'=>'unchecked']);
                            $option['score'] = 0;
                        }
                        break;
                    case 'Sequence':
                        $answer = answer::firstOrCreate(['solution'=>$optionIndex + 1]);
                        break;
                }
                if(array_key_exists('id',$option))
                    $optionModel = option::find($option['id']);
                else $optionModel = new option;
                $optionModel->question_id = $questionModel->id;
                $optionModel->text = $option['text'];
                $optionModel->expected_answer_id = $answer->id;
                $optionModel->score = $option['score'];
                $optionModel->save();
            }
        }
    }
    public function getTestToEdit($test)
    {
        $testLiveWire = [
            'id' => $test->id,
            'title' => $test->title,
            'maxAttempts' => $test->maxAttempts,
            'duration' => $test->duration,
            'resultsViewable' => $test->resultsViewable,
            'questions' => []
        ];
        foreach ($test->questions as $questionIndex => $question) {
            $testLiveWire['questions'][] = [
                'id' => $question->id,
                'type' => $question->type,
                'text' => $question->text,
                'options' => [],
                'right_answer_index' => '',
            ];
            foreach ($question->options as $optionIndex => $option) {
                $answer = $option->expected_answer;
                if ($question['type'] == "MultipleChoice") {
                    $testLiveWire['questions'][$questionIndex]['options'][] = [
                        'id' => $option->id,
                        'text' => $option->text,
                        'score' => $option->score,
                        'solution' => $answer->solution == "checked" ? $answer->id : '',
                    ];
                } else {
                    $testLiveWire['questions'][$questionIndex]['options'][] = [
                        'id' => $option->id,
                        'text' => $option->text,
                        'score' => $option->score
                    ];
                    if ($question['type'] == "OneChoice" || $question['type'] == "TrueFalse") {
                        if ($answer->solution == "checked")
                            $testLiveWire['questions'][$questionIndex]['right_option_index'] = $optionIndex;
                    }
                }
            }
        }
        return $testLiveWire;
    }

    public function getTestToWrite($test, $groupId)
    {
        $testLiveWire = [
            'group_id' => $groupId,
            'id' => $test->id,
            'title' => $test->title,
            'duration' => $test->duration,
            'resultsViewable' => $test->resultsViewable,
            'questions' => []
        ];
        foreach ($test->questions as $questionIndex => $question) {
            $testLiveWire['questions'][] = [
                'id' => $question->id,
                'text' => $question->text,
                'maxScore' => 0,
                'type' => $question->type,
                'options' => [],
                'achievedScore' => 0,
                'actual_ans' => ''
            ];
            foreach ($question->options as $option) {
                $testLiveWire['questions'][$questionIndex]['maxScore'] += $option->score;
                $answer = $option->expected_answer;
                $testLiveWire['questions'][$questionIndex]['options'][] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'expected_ans' => $answer->solution,
                    'actual_ans' => '',
                    'score' => $option->score
                ];
            }
            shuffle($testLiveWire['questions'][$questionIndex]['options']);
        }
        shuffle($testLiveWire['questions']);
        return $testLiveWire;
    }
}
