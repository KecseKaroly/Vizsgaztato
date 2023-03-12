<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class testAttempt extends Model
{
    use HasFactory;
    protected $table = 'test_attempts';

    protected $primaryKey = 'id';

    protected $fillable = [ 'submitted', 'maxScore', 'achievedScore','user_id', 'test_id'];

    public $timestamps = true;

    public function test() : BelongsTo
    {
        return $this->belongsTo(test::class);
    }
    public function given_answers() : HasMany
    {
        return $this->hasMany(given_answer::class, 'attempt_id', 'id');
    }
}
