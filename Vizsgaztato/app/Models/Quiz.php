<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $primaryKey = 'id';

    protected $fillable = ['module_id'];

    public $timestamps = true;

    public function module() : BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function questions() : HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
