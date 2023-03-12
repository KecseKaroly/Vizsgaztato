<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'maxAttempts', 'resultsViewable', 'duration', 'creator_id'];

    public $timestamps = true;

    public function questions(): HasMany
    {
        return $this->hasMany(question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class, 'creator_id', 'id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(attempt::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            group::class,
            'tests_groups',
            'test_id',
            'group_id')
            ->withPivot('enabled_from', 'enabled_until');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'courses_quizzes',
            'test_id',
            'module_id');
    }

    public function courseOfQuiz(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'courses_quizzes',
            'test_id',
            'course_id');
    }

    public function courseOfExam(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'courses_exams',
            'test_id',
            'course_id')
            ->withPivot('enabled_from', 'enabled_until');
    }
}
