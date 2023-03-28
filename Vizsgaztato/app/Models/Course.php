<?php

namespace App\Models;

use App\Models\test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'goal', 'creator_id'];

    public $timestamps = false;

    public function creator() :HasOne {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'courses_users',
            'course_id',
            'user_id')
            ->withPivot('id');
    }


    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'courses_groups',
            'course_id',
            'group_id')
            ->withPivot('id');
    }

    public function modules() : HasMany {
        return $this->hasMany(Module::class);
    }

    public function quizzes() : HasMany {
        return $this->hasMany(test::class)->where('is_exam', false);
    }

    public function exams() : HasMany {
        return $this->HasMany(test::class)->where('is_exam', true);
    }
}
