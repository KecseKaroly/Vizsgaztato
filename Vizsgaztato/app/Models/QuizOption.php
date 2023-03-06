<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizOption extends Model
{
    use HasFactory;

    protected $table = 'quiz_options';

    protected $primaryKey = 'id';

    protected $fillable = ['text', 'expected_answer_id','quiz_question_id'];

    public $timestamps = false;

    public function question() : BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'id', 'quiz_question_id');
    }
    public function expected_answer() : HasOne
    {
        return $this->hasOne(answer::class, 'id', 'expected_answer_id');
    }
}
