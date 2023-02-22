<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class option extends Model
{
    use HasFactory;
    protected $table = 'options';

    protected $primaryKey = 'id';

    protected $fillable = ['text', 'score', 'expected_answer_id','question_id'];

    public $timestamps = false;

    public function question() : BelongsTo
    {
        return $this->belongsTo(question::class);
    }
    public function expected_answer() : HasOne
    {
        return $this->hasOne(answer::class, 'id', 'expected_answer_id');
    }

    public function given_answers() : HasMany
    {
        return $this->hasMany(given_answer::class, 'option_id', 'id');
    }
}
