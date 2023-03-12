<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesQuizzes extends Model
{
    use HasFactory;
    protected $table = 'courses_quizzes';

    protected $primaryKey = 'id';

    protected $fillable = ['course_id', 'test_id', 'module_id',];

    public $timestamps = false;
}
