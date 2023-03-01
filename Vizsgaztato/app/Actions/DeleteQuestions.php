<?php

namespace App\Actions;

use App\Models\question;

class DeleteQuestions {
    public function delete($questions): void
    {

        foreach ($questions as $question) {
            $question = question::find($question['id'])->delete();
        }
    }
}
