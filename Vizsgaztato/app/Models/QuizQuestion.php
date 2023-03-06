<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $primaryKey = 'id';

    protected $fillable = ['quiz_id', 'text', 'type'];

    public $timestamps = true;

    public function quiz() : BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options() : HasMany
    {
        return $this->hasMany(QuizOption::class);
    }
}
