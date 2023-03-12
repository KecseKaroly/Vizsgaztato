<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesExams extends Model
{

    use HasFactory;

    protected $table = 'courses_exams';

    protected $primaryKey = 'id';

    protected $fillable = ['course_id', 'test_id', 'enabled_from', 'enabled_until'];

    public $timestamps = false;
}
